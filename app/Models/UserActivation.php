<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

class UserActivation extends Model
{
    use HasFactory;
    protected $table = 'user_activations';
    public $timestamps = false;
    protected $fillable = [
        'id_user','activation_code','expires','type'
    ];

    protected function getToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    public function getActivation(User $user,$type)
    {
        return self::where(['id_user' => $user->id,'type' => $type])->first();
    }

    public function createActivation($user,$type)
    {

        $activation = $this->getActivation($user,$type);

        if (!$activation) {
            return $this->createToken($user,$type);
        }
        return $this->regenerateToken($user,$type);

    }

    private function regenerateToken($user,$type)
    {

        $token = $this->getToken();
        self::where(['id_user' => $user->id,'type' => $type])->update([
            'activation_code' => $token,
            'expires' => (new Carbon())->addMinutes(10)
        ]);
        return $token;
    }

    private function createToken($user,$type)
    {
        $token = $this->getToken();
        self::insert([
            'id_user' => $user->id,
            'activation_code' => $token,
            'type' => $type,
            'expires' => (new Carbon())->addMinutes(10)
        ]);
        return $token;
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id_user','id');
    }
}
