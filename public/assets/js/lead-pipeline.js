/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Lead Pipeline Interactive JS - AJAX Stage Transitions & Modals
 */

function updateLeadStage(leadId, newStage, statusText, notes) {
    if (!confirm('Are you sure you want to transition this lead to stage: ' + newStage + '?')) {
        return;
    }

    const base = window.SVPL_BASE || '';
    $.ajax({
        url: base + '/admin/leads/update-stage',
        method: 'POST',
        data: {
            lead_id: leadId,
            stage: newStage,
            status: statusText,
            notes: notes || 'Updated via Admin Pipeline'
        },
        dataType: 'json',
        success: function (res) {
            if (res.status) {
                alert('Success: ' + res.message);
                location.reload();
            } else {
                alert('Error: ' + res.message);
            }
        },
        error: function () {
            alert('Server error occurred while updating stage.');
        }
    });
}
