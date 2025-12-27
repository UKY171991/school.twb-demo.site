/**
 * School Cascade JavaScript
 * Handles cascading dropdowns for School -> Grade -> Subject
 */

class SchoolCascade {
    constructor(options = {}) {
        this.schoolSelector = options.schoolSelector || '#school_id';
        this.gradeSelector = options.gradeSelector || '#grade_id';
        this.subjectSelector = options.subjectSelector || '#subject_id';
        this.baseUrl = options.baseUrl || '/api';
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.triggerInitialLoad();
    }
    
    bindEvents() {
        // School change event
        $(this.schoolSelector).on('change', (e) => {
            this.loadGrades($(e.target).val());
        });
        
        // Grade change event
        $(this.gradeSelector).on('change', (e) => {
            this.loadSubjects($(e.target).val());
        });
    }
    
    loadGrades(schoolId) {
        const gradeSelect = $(this.gradeSelector);
        const subjectSelect = $(this.subjectSelector);
        
        // Clear dependent dropdowns
        this.clearSelect(gradeSelect, 'Loading grades...');
        this.clearSelect(subjectSelect, 'Select grade first');
        
        if (!schoolId) {
            this.clearSelect(gradeSelect, 'Select Grade');
            return;
        }
        
        $.ajax({
            url: `${this.baseUrl}/schools/${schoolId}/grades`,
            type: 'GET',
            success: (data) => {
                this.populateSelect(gradeSelect, data, 'Select Grade', (item) => {
                    let text = item.name;
                    if (item.section) {
                        text += ` - ${item.section}`;
                    }
                    return text;
                });
            },
            error: () => {
                this.clearSelect(gradeSelect, 'Error loading grades');
            }
        });
    }
    
    loadSubjects(gradeId) {
        const subjectSelect = $(this.subjectSelector);
        
        this.clearSelect(subjectSelect, 'Loading subjects...');
        
        if (!gradeId) {
            this.clearSelect(subjectSelect, 'Select Subject');
            return;
        }
        
        $.ajax({
            url: `${this.baseUrl}/grades/${gradeId}/subjects`,
            type: 'GET',
            success: (data) => {
                this.populateSelect(subjectSelect, data, 'Select Subject', (item) => {
                    let text = item.name;
                    if (item.code) {
                        text += ` (${item.code})`;
                    }
                    return text;
                });
            },
            error: () => {
                this.clearSelect(subjectSelect, 'Error loading subjects');
            }
        });
    }
    
    clearSelect(selectElement, placeholder) {
        selectElement.html(`<option value="">${placeholder}</option>`);
    }
    
    populateSelect(selectElement, data, placeholder, textFormatter) {
        selectElement.html(`<option value="">${placeholder}</option>`);
        
        data.forEach(item => {
            const text = textFormatter ? textFormatter(item) : item.name;
            selectElement.append(`<option value="${item.id}">${text}</option>`);
        });
    }
    
    triggerInitialLoad() {
        // Trigger change events if values are already selected
        const schoolId = $(this.schoolSelector).val();
        if (schoolId) {
            this.loadGrades(schoolId);
            
            // Wait a bit then trigger grade change if grade is selected
            setTimeout(() => {
                const gradeId = $(this.gradeSelector).val();
                if (gradeId) {
                    this.loadSubjects(gradeId);
                }
            }, 500);
        }
    }
}

// Auto-initialize if elements exist
$(document).ready(function() {
    if ($('#school_id').length && $('#grade_id').length) {
        new SchoolCascade();
    }
});