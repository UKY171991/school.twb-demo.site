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
        $settings = $request->input('settings', []);
        
        foreach ($settings as $key => $value) {
            $setting = SystemSetting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            }
        }

        return redirect()->route('settings.index')
                        ->with('success', 'Settings updated successfully.');
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
