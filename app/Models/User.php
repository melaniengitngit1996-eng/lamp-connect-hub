<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'member_id',
        'local_church_id',
        'name',
        'email',
        'password',
        'status',
        'username'
    ];

    protected $appends = [
        'joined_ago',
        'initials'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function localChurch()
    {
        return $this->belongsTo(LocalChurch::class);
    }

    public function clusters()
    {
        return $this->belongsToMany(Cluster::class);
    }

    public function ministries()
    {
        return $this->belongsToMany(Ministry::class);
    }

    public function principals(): array
    {
        $principals = [
            [
                'type' => 'user',
                'id' => $this->id,
            ],
        ];

        if ($this->local_church_id) {
            $principals[] = [
                'type' => 'church',
                'id' => $this->local_church_id,
            ];
        }

        foreach ($this->clusters as $cluster) {
            $principals[] = [
                'type' => 'cluster',
                'id' => $cluster->id,
            ];
        }

        foreach ($this->ministries as $ministry) {
            $principals[] = [
                'type' => 'ministry',
                'id' => $ministry->id,
            ];
        }

        return $principals;
    }

    public function getJoinedAgoAttribute()
    {
        return $this->created_at
            ->diffForHumans(short: true);
    }

    public function getInitialsAttribute(): string
    {
        $parts = collect(
            preg_split('/\s+/', trim($this->name))
        )->filter();

        if ($parts->count() === 1) {
            return strtoupper(
                substr($parts->first(), 0, 2)
            );
        }

        return strtoupper(
            substr($parts->first(), 0, 1)
                . substr($parts->last(), 0, 1)
        );
    }

    public function fileActivities()
    {
        return $this->hasMany(FileActivity::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function driveFavorites()
    {
        return $this->hasMany(DriveFavorite::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(
            Conversation::class,
            'conversation_members'
        )
            ->using(ConversationMember::class)
            ->withPivot([
                'role',
                'last_read_message_id',
                'joined_at',
            ])
            ->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
}
