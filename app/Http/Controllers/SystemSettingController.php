<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\GradingSystem;
use App\Models\MarkingScheme;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        $gradingSystems = GradingSystem::where('is_active', true)->get();
        $markingSchemes = MarkingScheme::where('is_active', true)->get();
        
        return view('settings.index', compact('settings', 'gradingSystems', 'markingSchemes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings.school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'settings.school_favicon' => 'nullable|image|mimes:ico,png,jpg,gif,svg|max:1024',
        ], [
            'settings.school_logo.image' => 'The school logo must be an image file.',
            'settings.school_logo.mimes' => 'The school logo must be a file of type: jpeg, png, jpg, gif, svg.',
            'settings.school_logo.max' => 'The school logo may not be greater than 2MB.',
            'settings.school_favicon.image' => 'The favicon must be an image file.',
            'settings.school_favicon.mimes' => 'The favicon must be a file of type: ico, png, jpg, gif, svg.',
            'settings.school_favicon.max' => 'The favicon may not be greater than 1MB.',
        ]);

        $settings = $request->input('settings', []);
        
        try {
            // Handle logo upload
            if ($request->hasFile('settings.school_logo')) {
                $logoPath = $this->handleImageUpload(
                    $request->file('settings.school_logo'),
                    'school_logo',
                    SystemSetting::get('school_logo')
                );
                if ($logoPath) {
                    $settings['school_logo'] = $logoPath;
                }
            }
            
            // Handle favicon upload
            if ($request->hasFile('settings.school_favicon')) {
                $faviconPath = $this->handleImageUpload(
                    $request->file('settings.school_favicon'),
                    'school_favicon',
                    SystemSetting::get('school_favicon')
                );
                if ($faviconPath) {
                    $settings['school_favicon'] = $faviconPath;
                }
            }
            
            foreach ($settings as $key => $value) {
                $setting = SystemSetting::where('key', $key)->first();
                if ($setting) {
                    $setting->update(['value' => $value]);
                } else {
                    // Create new setting if it doesn't exist
                    SystemSetting::create([
                        'key' => $key,
                        'value' => $value,
                        'type' => 'string',
                        'group' => 'school',
                        'label' => ucfirst(str_replace('_', ' ', $key))
                    ]);
                }
            }

            return redirect()->route('settings.index')
                            ->with('success', 'Settings updated successfully.');
                            
        } catch (\Exception $e) {
            return redirect()->route('settings.index')
                            ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Handle image upload with proper error handling
     */
    private function handleImageUpload($file, $prefix, $oldFilePath = null)
    {
        try {
            // Delete old file if exists
            if ($oldFilePath && $oldFilePath !== 'vendor/adminlte/dist/img/AdminLTELogo.png') {
                $this->deleteOldFile($oldFilePath);
            }
            
            $fileName = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            $uploadPath = public_path('uploads/school');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move file
            $file->move($uploadPath, $fileName);
            
            return 'uploads/school/' . $fileName;
            
        } catch (\Exception $e) {
            \Log::error('File upload failed: ' . $e->getMessage());
            throw new \Exception('Failed to upload file: ' . $e->getMessage());
        }
    }

    /**
     * Delete old file from server
     */
    private function deleteOldFile($filePath)
    {
        if ($filePath && file_exists(public_path($filePath))) {
            try {
                unlink(public_path($filePath));
            } catch (\Exception $e) {
                // Log error but don't fail the upload
                \Log::warning('Failed to delete old file: ' . $filePath . ' - ' . $e->getMessage());
            }
        }
    }

    /**
     * Reset a specific setting to default
     */
    public function resetSetting(Request $request)
    {
        $key = $request->input('key');
        
        if (in_array($key, ['school_logo', 'school_favicon'])) {
            $setting = SystemSetting::where('key', $key)->first();
            if ($setting && $setting->value) {
                // Delete the file before resetting
                $this->deleteOldFile($setting->value);
                $setting->delete();
            }
        }
        
        return redirect()->route('settings.index')
                        ->with('success', 'Setting reset successfully.');
    }

    public function gradingSettings()
    {
        $gradingSystems = GradingSystem::orderBy('sort_order')->orderBy('min_percentage', 'desc')->get();
        $currentScheme = SystemSetting::get('current_grading_scheme', 'default');
        
        return view('settings.grading', compact('gradingSystems', 'currentScheme'));
    }

    public function updateGradingSettings(Request $request)
    {
        $request->validate([
            'current_grading_scheme' => 'required|string',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'grade_calculation_method' => 'required|string|in:percentage,points,weighted'
        ]);

        SystemSetting::set('current_grading_scheme', $request->current_grading_scheme, 'string', 'grading', 'Current Grading Scheme');
        SystemSetting::set('pass_percentage', $request->pass_percentage, 'float', 'grading', 'Pass Percentage');
        SystemSetting::set('grade_calculation_method', $request->grade_calculation_method, 'string', 'grading', 'Grade Calculation Method');

        return redirect()->route('settings.grading')
                        ->with('success', 'Grading settings updated successfully.');
    }

    public function markingSettings()
    {
        $markingSchemes = MarkingScheme::all();
        $currentScheme = SystemSetting::get('current_marking_scheme', 'percentage');
        
        return view('settings.marking', compact('markingSchemes', 'currentScheme'));
    }

    public function updateMarkingSettings(Request $request)
    {
        $request->validate([
            'current_marking_scheme' => 'required|string',
            'decimal_places' => 'required|integer|min:0|max:4',
            'rounding_method' => 'required|string|in:round,floor,ceil'
        ]);

        SystemSetting::set('current_marking_scheme', $request->current_marking_scheme, 'string', 'marking', 'Current Marking Scheme');
        SystemSetting::set('decimal_places', $request->decimal_places, 'integer', 'marking', 'Decimal Places');
        SystemSetting::set('rounding_method', $request->rounding_method, 'string', 'marking', 'Rounding Method');

        return redirect()->route('settings.marking')
                        ->with('success', 'Marking settings updated successfully.');
    }
}
