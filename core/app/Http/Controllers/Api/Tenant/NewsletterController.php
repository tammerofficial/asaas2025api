<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\Newsletter\StoreNewsletterRequest;
use App\Http\Requests\Api\Tenant\Newsletter\UpdateNewsletterRequest;
use App\Http\Resources\Api\Tenant\NewsletterResource;
use App\Models\Newsletter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class NewsletterController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_newsletters_' . request()->get('page', 1), 300, function () {
                return Newsletter::select(['id', 'email', 'status', 'created_at', 'updated_at'])->latest()->paginate(20);
            });
            return NewsletterResource::collection($cached);
        } catch (\Exception $e) {
            return NewsletterResource::collection(Newsletter::latest()->paginate(20));
        }
    }
    public function store(StoreNewsletterRequest $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $newsletter = Newsletter::create($request->validated());
        $this->clearCache('tenant_newsletters*');
        return response()->json(['success' => true, 'message' => 'Newsletter subscription created successfully', 'data' => new NewsletterResource($newsletter)], 201);
    }
    public function show(Newsletter $newsletter): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_newsletter_{$newsletter->id}", 600, function () use ($newsletter) { return $newsletter; });
            return response()->json(['success' => true, 'message' => 'Newsletter retrieved successfully', 'data' => new NewsletterResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Newsletter retrieved successfully', 'data' => new NewsletterResource($newsletter)]);
        }
    }
    public function update(UpdateNewsletterRequest $request, Newsletter $newsletter): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $newsletter->update($request->validated());
        $this->clearCache('tenant_newsletters*');
        $this->clearCache("tenant_newsletter_{$newsletter->id}*");
        return response()->json(['success' => true, 'message' => 'Newsletter updated successfully', 'data' => new NewsletterResource($newsletter)]);
    }
    public function destroy(Newsletter $newsletter): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $id = $newsletter->id;
        $newsletter->delete();
        $this->clearCache('tenant_newsletters*');
        $this->clearCache("tenant_newsletter_{$id}*");
        return response()->json(['success' => true, 'message' => 'Newsletter deleted successfully']);
    }
    public function unsubscribe(Newsletter $newsletter): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $newsletter->update(['status' => 0]);
        $this->clearCache('tenant_newsletters*');
        $this->clearCache("tenant_newsletter_{$newsletter->id}*");
        return response()->json(['success' => true, 'message' => 'Newsletter unsubscribed successfully', 'data' => new NewsletterResource($newsletter)]);
    }
}

