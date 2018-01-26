<?php
namespace App\Entities;

use App\Order;
use App\Tipo;
use App\Prime;
use App\Shop;
use App\Level;
use Carbon\Carbon;
use App\TipoCliente;
use App\Liquidacion;
use App\LiquidacionTercero;
use App\LiquidacionDetalle;
use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Tercero extends Model implements AuthenticatableContract, CanResetPasswordContract, HasRoleAndPermissionContract {

    use Authenticatable, CanResetPassword, HasRoleAndPermission;

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'terceros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $dates = ['fecha_nacimiento'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    /*public function setCuentaAttribute($path)
    {
        return $path->getClientOriginalName();
        $this->attributes['cuenta'] = Carbon::now()->second . $path->getClientOriginalName();
        $name = Carbon::now()->second . $path->getClientOriginalName();
        \Storage::disk('local')->put($name, \File::get($path));
    }

    public function setCedulaAttribute($path)
    {
        return $path->getClientOriginalName();
        $this->attributes['cedula'] = Carbon::now()->second . $path->getClientOriginalName();
        $name = Carbon::now()->second . $path->getClientOriginalName();
        \Storage::disk('local')->put($name, \File::get($path));
    }

    public function setRutAttribute($path)
    {
        return $path;
        $this->attributes['rut'] = Carbon::now()->second . $path->getClientOriginalName();
        $name = Carbon::now()->second . $path->getClientOriginalName();
        \Storage::disk('local')->put($name, \File::get($path));
    }*/

    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function getId()
    {
        return $this->id;
    }

    public function cargo()
    {
        return $this->belongsTo('App\Entities\Cargo');
    }

    public function ciudad()
    {
        return $this->belongsTo('App\Entities\Ciudad');
    }

    public function resolucion()
    {
        return $this->belongsTo('App\Entities\Resolucion');
    }

    public function sector()
    {
        return $this->belongsTo('App\Entities\Sector');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Entities\Sucursal');
    }


    public function zona()
    {
        return $this->belongsTo('App\Entities\Zona');
    }

    public function scopeTipoUsuario($query, $type)
    {
        return $query->where('tipo_id', $type);
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombres;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'tercero_id');
    }

    public function networks()
    {
        return $this->belongsToMany(Network::class, 'terceros_networks', 'customer_id', 'network_id')->withPivot('network_id', 'padre_id')->withTimestamps();
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Tipo::class, 'tipo_cliente_id');
    }

    public function liquidaciones()
    {
        return $this->hasMany(Liquidacion::class, 'usuario_id');
    }

    public function primes()
    {
        return $this->hasMany(Prime::class, 'tercero_id');
    }

    public function levels()
    {
        return $this->hasMany(Level::class, 'tercero_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'tercero_id');
    }

    public function liquidacion_tercero()
    {
        return $this->hasMany(LiquidacionTercero::class, 'tercero_id');
    }

    public function liquidacion_detalle()
    {
        return $this->hasMany(LiquidacionDetalle::class, 'tercero_id');
    }
}
