<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Qualification Service - Automatic 3-Customer Rule Evaluation & Rank Progression
 */

namespace App\Services;

use App\Models\Advisor;
use App\Models\Setting;
use App\Models\AuditLog;

class QualificationService
{
    /**
     * Check and upgrade advisor qualification status upon completing a customer lead
     */
    public static function checkAndUpgrade(int $advisorId): array
    {
        $advisor = Advisor::findById($advisorId);
        if (!$advisor) {
            return ['status' => false, 'message' => 'Advisor not found'];
        }

        $currentCount = Advisor::incrementDirectCustomerCount($advisorId);
        $requiredCount = (int) Setting::get('advisor_required_customers', 3);

        $upgraded = false;
        if ($currentCount >= $requiredCount && $advisor['status'] !== 'QUALIFIED' && $advisor['status'] !== 'PROMOTED') {
            Advisor::markQualified($advisorId);
            $upgraded = true;
            AuditLog::log((int)$advisor['user_id'], 'ADVISOR_QUALIFIED', 'ADVISOR', $advisorId, "Advisor qualified after completing {$currentCount} direct customers");
        }

        return [
            'status' => true,
            'advisor_id' => $advisorId,
            'direct_customers' => $currentCount,
            'required' => $requiredCount,
            'upgraded' => $upgraded,
            'current_status' => $upgraded ? 'QUALIFIED' : $advisor['status'],
        ];
    }
}
