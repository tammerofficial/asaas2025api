<?php
namespace App\Http\Controllers\Api\Tenant;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\Tenant\SupportTicket\StoreSupportTicketRequest;
use App\Http\Requests\Api\Tenant\SupportTicket\UpdateSupportTicketRequest;
use App\Http\Resources\Api\Tenant\SupportTicketResource;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
class SupportTicketController extends BaseApiController
{
    public function index(): AnonymousResourceCollection
    {
        try {
            $cached = $this->remember('tenant_support_tickets_' . request()->get('page', 1), 300, function () {
                return SupportTicket::select(['id', 'title', 'via', 'operating_system', 'user_agent', 'description', 'subject', 'status', 'priority', 'user_id', 'admin_id', 'department_id', 'created_at', 'updated_at'])->with(['user:id,name,email', 'admin:id,name', 'department:id,name'])->latest()->paginate(20);
            });
            return SupportTicketResource::collection($cached);
        } catch (\Exception $e) {
            return SupportTicketResource::collection(SupportTicket::with(['user', 'admin', 'department'])->latest()->paginate(20));
        }
    }
    public function store(StoreSupportTicketRequest $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $ticket = SupportTicket::create($request->validated());
        $this->clearCache('tenant_support_tickets*');
        return response()->json(['success' => true, 'message' => 'Support ticket created successfully', 'data' => new SupportTicketResource($ticket->load(['user', 'admin', 'department']))], 201);
    }
    public function show(SupportTicket $supportTicket): JsonResponse
    {
        try {
            $cached = $this->remember("tenant_support_ticket_{$supportTicket->id}", 600, function () use ($supportTicket) {
                $supportTicket->load(['user:id,name,email', 'admin:id,name', 'department:id,name', 'messages']);
                return $supportTicket;
            });
            return response()->json(['success' => true, 'message' => 'Support ticket retrieved successfully', 'data' => new SupportTicketResource($cached)]);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Support ticket retrieved successfully', 'data' => new SupportTicketResource($supportTicket->load(['user', 'admin', 'department', 'messages']))]);
        }
    }
    public function update(UpdateSupportTicketRequest $request, SupportTicket $supportTicket): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $supportTicket->update($request->validated());
        $this->clearCache('tenant_support_tickets*');
        $this->clearCache("tenant_support_ticket_{$supportTicket->id}*");
        return response()->json(['success' => true, 'message' => 'Support ticket updated successfully', 'data' => new SupportTicketResource($supportTicket->load(['user', 'admin', 'department']))]);
    }
    public function addMessage(SupportTicket $supportTicket, \Illuminate\Http\Request $request): JsonResponse
    {
        if (!tenant()) return response()->json(['success' => false, 'message' => 'Tenant context not found'], 404);
        $validated = $request->validate(['message' => ['required', 'string'], 'attachment' => ['nullable', 'string'], 'notify' => ['nullable', 'boolean'], 'type' => ['nullable', 'string', 'in:user,admin']]);
        $message = SupportTicketMessage::create(array_merge($validated, ['support_ticket_id' => $supportTicket->id, 'user_id' => auth('sanctum')->id(), 'type' => $validated['type'] ?? 'user']));
        $this->clearCache("tenant_support_ticket_{$supportTicket->id}*");
        return response()->json(['success' => true, 'message' => 'Message added successfully', 'data' => $message]);
    }
}

