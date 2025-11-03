<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Wallet\UpdateWalletRequest;
use App\Http\Resources\Api\Tenant\WalletResource;
use Modules\Wallet\Entities\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WalletController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_wallets_' . request()->get('page', 1), 300, function () {
                return Wallet::select(['id', 'user_id', 'balance', 'status', 'created_at', 'updated_at'])
                    ->with(['user:id,name,email'])->latest()->paginate(20);
            });
            return WalletResource::collection($cached);
        } catch (\Exception $e) {
            return WalletResource::collection(Wallet::with('user')->latest()->paginate(20));
        }
    }

    public function show(Wallet $wallet): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_wallet_{$wallet->id}", 600, function () use ($wallet) {
                $wallet->load(['user', 'walletSettings']);
                return $wallet;
            });
            return response()->json(['success' => true, 'message' => 'Wallet retrieved successfully', 'data' => new WalletResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Wallet retrieved successfully', 'data' => new WalletResource($wallet->load(['user', 'walletSettings']))]);
        }
    }

    public function update(UpdateWalletRequest $request, Wallet $wallet): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $wallet->update($request->validated());
        $this->clearCache('tenant_wallets*');
        $this->clearCache("tenant_wallet_{$wallet->id}*");
        return response()->json(['success' => true, 'message' => 'Wallet updated successfully', 'data' => new WalletResource($wallet->load('user'))]);
    }

    public function addBalance(\Illuminate\Http\Request $request, Wallet $wallet): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $validated = $request->validate(['amount' => ['required', 'numeric', 'min:0']]);
        $wallet->increment('balance', $validated['amount']);
        $this->clearCache('tenant_wallets*');
        $this->clearCache("tenant_wallet_{$wallet->id}*");
        return response()->json(['success' => true, 'message' => 'Balance added successfully', 'data' => new WalletResource($wallet->fresh()->load('user'))]);
    }

    public function deductBalance(\Illuminate\Http\Request $request, Wallet $wallet): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $validated = $request->validate(['amount' => ['required', 'numeric', 'min:0', 'max:' . $wallet->balance]]);
        $wallet->decrement('balance', $validated['amount']);
        $this->clearCache('tenant_wallets*');
        $this->clearCache("tenant_wallet_{$wallet->id}*");
        return response()->json(['success' => true, 'message' => 'Balance deducted successfully', 'data' => new WalletResource($wallet->fresh()->load('user'))]);
    }
}

