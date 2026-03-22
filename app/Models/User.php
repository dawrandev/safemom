<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'phone',
        'telegram_id',
        'password',
        'role',
        'doctor_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    /**
     * Get the doctor assigned to this patient
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get all patients assigned to this doctor
     */
    public function patients()
    {
        return $this->hasMany(User::class, 'doctor_id');
    }

    /**
     * Get the user's profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get all vital signs for the user
     */
    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    /**
     * Get all AI diagnoses for the user
     */
    public function aiDiagnoses()
    {
        return $this->hasMany(AiDiagnosis::class);
    }

    /**
     * Get all kick count sessions for the user
     */
    public function kickCounts()
    {
        return $this->hasMany(KickCount::class);
    }

    /**
     * Get all medications for the user
     */
    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    /**
     * Get all messages sent by the user
     */
    public function sentMessages()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    /**
     * Get all messages received by the user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    /**
     * Get all doctor notes written by the user (if doctor)
     */
    public function doctorNotesWritten()
    {
        return $this->hasMany(DoctorNote::class, 'doctor_id');
    }

    /**
     * Get all doctor notes about the user (if patient)
     */
    public function doctorNotesReceived()
    {
        return $this->hasMany(DoctorNote::class, 'patient_id');
    }
}
