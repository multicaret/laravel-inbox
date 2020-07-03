<?php

namespace Multicaret\Inbox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 */
class Thread extends Model
{
    use SoftDeletes;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array $attributes
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('inbox.tables.threads');

        parent::__construct($attributes);
    }

    /**
     * The attributes that can be set with Mass Assignment.
     *
     * @var array
     */
    protected $fillable = ['subject', 'user_id'];

    /**
     * Messages relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(config('inbox.models.message'));
    }

    /**
     * Participants relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany(config('auth.providers.users.model'), config('inbox.tables.participants'),
            'thread_id', 'user_id')
                    ->withPivot('seen_at', 'deleted_at')
                    ->withTimestamps();
    }

    /**
     * Recipients of this message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipients()
    {
        return $this->participants()->where('user_id', '!=', $this->user_id);
    }

    /**
     * User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * See if the current thread is unread by the user.
     *
     * @param integer $userId
     *
     * @return bool
     */
    public function isUnread($userId = null)
    {
        $userId = $userId ?? auth()->id();

        $participant = $this->participants()
                            ->where('user_id', $userId)
                            ->first();

        if ($participant && $participant->pivot->seen_at === null) {
            return true;
        }

        return false;
    }

    /**
     * Adds users to this thread
     *
     * @param array $participants list of all participants
     *
     * @return void
     */
    public function addParticipants(array $participants)
    {
        if (count($participants)) {
            $participantClass = config('inbox.models.participant');
            foreach ($participants as $user_id) {
                $participant = $participantClass::firstOrCreate([
                    'thread_id' => $this->id,
                    'user_id' => $user_id,
                ]);

                $participant->seen_at = null;
                $participant->save();
            }
        }
    }

    /**
     * Restores all participants within a thread that has a new message
     */
    public function activateAllParticipants()
    {
        $participants = $this->participants()->get();

        foreach ($participants as $participant) {
            $participant = $participant->pivot;
            if ($participant) {
                $participant->deleted_at = null;
                $participant->seen_at = null;
                $participant->save();
            }
        }
    }

    /**
     * Get last message associated with thread.
     *
     * @return object
     */
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Scope a query to only include thread form custom users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param                                       $users
     *
     * @return void
     */
    public function scopeFrom($query, $users)
    {
        if ( ! is_array($users)) {
            $users = [$users];
        }

        $query->whereIn($this->table . '.user_id', $users);
    }

    /**
     * Scope a query to only include thread sent to custom users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param                                       $users
     *
     * @return void
     */
    public function scopeTo($query, $users)
    {
        if ( ! is_array($users)) {
            $users = [$users];
        }

        $participantsTable = config('inbox.tables.participants');

        $query->select($this->table . '.*')
              ->join($participantsTable, "{$this->table}.id", '=', "{$participantsTable}.thread_id")
              ->whereIn("{$participantsTable}.user_id", $users);
    }

    /**
     * Scope a query to only include seen thread.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    public function scopeSeen($query)
    {
        $query->whereNotNull(config('inbox.tables.participants') . '.seen_at');
    }
}
