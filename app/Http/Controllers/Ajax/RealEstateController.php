<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectCatalogue;

class RealEstateController extends Controller
{
    public function getPropertyGroup(Request $request)
    {
        $id = $request->input('id');
        $typeCode = $request->input('type_code');

        if ($typeCode) {
            $type = \App\Models\ProjectType::with('group')->where('code', $typeCode)->first();
            if ($type && $type->group) {
                return response()->json([
                    'property_group' => $type->group->code
                ]);
            }
        }

        // Fetch catalogue with its propertyGroup relation
        $catalogue = ProjectCatalogue::with('propertyGroup')->find($id);

        if ($catalogue) {
            return response()->json([
                'property_group' => $catalogue->propertyGroup->code ?? '',
                'transaction_type' => $catalogue->transaction_type->value ?? '',
                'type_code' => $catalogue->type_code ?? '',
            ]);
        }

        return response()->json(['property_group' => 'none']);
    }
}
