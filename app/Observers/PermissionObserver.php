<?php

namespace App\Observers;

use App\Models\Permission;
use App\Models\UserCatalogue;
use Illuminate\Support\Facades\DB;

class PermissionObserver
{
    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        $adminCatalogue = UserCatalogue::where('id', 1)->first();

        if ($adminCatalogue) {
            DB::table('user_catalogue_permission')->updateOrInsert(
                [
                    'user_catalogue_id' => $adminCatalogue->id,
                    'permission_id' => $permission->id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
