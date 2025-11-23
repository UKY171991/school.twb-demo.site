<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralSettingsController extends BaseController
{
    /**
     * Display general settings page
     */
    public function index()
    {
        $settings = GeneralSetting::all()->groupBy('group');
        
        return view('admin.settings.general.index', array_merge(
            $this->getCommonViewData(),
            compact('settings')
        ));
    }

    /**
     * Update general settings
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'settings' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            foreach ($request->settings as $key => $value) {
                GeneralSetting::set($key, $value);
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get settings by group (AJAX)
     */
    public function getByGroup(Request $request, $group)
    {
        try {
            $settings = GeneralSetting::where('group', $group)->get();

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update a single setting (AJAX)
     */
    public function updateSetting(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'key' => 'required|string',
                'value' => 'required',
                'type' => 'required|in:text,number,boolean,json',
                'group' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            GeneralSetting::set(
                $request->key,
                $request->value,
                $request->type,
                $request->group
            );

            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a setting (AJAX)
     */
    public function destroy(Request $request, $id)
    {
        try {
            $setting = GeneralSetting::findOrFail($id);
            $setting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting setting: ' . $e->getMessage()
            ], 500);
        }
    }
}
