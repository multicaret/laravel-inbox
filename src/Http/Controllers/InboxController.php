<?php

namespace Multicaret\Inbox\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Multicaret\Inbox\Models\Participant;
use Multicaret\Inbox\Models\Thread;

class InboxController extends Controller
{
    protected $threadClass, $participantClass;

    public function __construct()
    {
        parent::__construct();

        $this->threadClass = config('inbox.models.thread');
        $this->participantClass = config('inbox.models.participant');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if (request()->has('sent')) {
            $threads = $user->sent();
        } else {
            $threads = $user->received();
        }

        $threads = $threads->paginate(config('inbox.paginate', 10));


        return view('inbox::index', compact('threads'));
    }

    /**
     *
     */
    public function create()
    {
        return view('inbox::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
            'recipients' => 'required|array',
        ]);

        $thread = auth()->user()
                        ->subject($request->subject)
                        ->writes($request->body)
                        ->to($request->recipients)
                        ->send();

        return redirect()
            ->route(config('inbox.route.name') . 'inbox.index')
            ->with('message', [
                'type' => $thread ? 'success' : 'error',
                'text' => $thread ? trans('inbox::messages.thread.sent') : trans('inbox::messages.thread.whoops'),
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function show($thread)
    {
        $threadClass = $this->threadClass;
        $thread = $threadClass::findOrFail($thread);

        $messages = $thread->messages()->get();

        $seen = $thread->participants()
                       ->where('user_id', auth()->id())
                       ->first();

        if ($seen && $seen->pivot) {
            $seen->pivot->seen_at = Carbon::now();
            $seen->pivot->save();
        } else {
            return abort(404);
        }

        return view('inbox::show', compact('messages', 'thread'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Thread                    $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, $thread)
    {
        $threadClass = $this->threadClass;
        $thread = $threadClass::findOrFail($thread);

        $request->validate([
            'body' => 'required',
        ]);

        $message = auth()->user()
                         ->writes($request->body)
                         ->reply($thread);

        return redirect()
            ->route(config('inbox.route.name') . 'inbox.show', $thread)
            ->with('message', [
                'type' => $message ? 'success' : 'error',
                'text' => $message ? trans('inbox::messages.message.sent') : trans('inbox::messages.message.whoops'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($thread)
    {
        $threadClass = $this->threadClass;
        $thread = $threadClass::findOrFail($thread);

        $message = Participant::where('user_id', auth()->id())
                              ->where('thread_id', $thread->id)
                              ->firstOrFail();

        $deleted = $message->delete();

        return redirect()
            ->route(config('inbox.route.name') . 'inbox.index')
            ->with('message', [
                'type' => $deleted ? 'success' : 'error',
                'text' => $deleted ? trans('inbox::messages.thread.deleted') : trans('inbox::messages.thread.whoops'),
            ]);
    }
}
