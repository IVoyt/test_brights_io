<?php

namespace app\models;

use app\base\Model;

/**
 * @property int    $id
 * @property string $email_address
 * @property string $name
 * @property string $created_at
 * @property int    $sort_order
 */
final class ListContact extends Model
{
    protected string $tableName = 'list_contact';
    protected array  $fillable  = [
        'email_address',
        'name',
        'created_at',
        'sort_order',
    ];
}
