<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController as AppBaseController;
use App\Models\SettingModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use Psr\Log\LoggerInterface;

abstract class BaseController extends AppBaseController
{
    protected $data = [];

    /**
     * Initialize controller, check authentication, and load shared data.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function initController($request, $response, $logger): void
    {
        parent::initController($request, $response, $logger);

        helper('text');

        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu.');
            redirect()->to('/login')->send();
            exit;
        }

        // Session may be stale: roles can be revoked or accounts disabled/deleted from
        // the Users page. Re-verify against the database so changes take effect immediately
        // (without requiring logout/login).
        $account = new UserModel()
            ->select('id, role, is_active')
            ->find((int)session()->get('userId'));

        if (!$account || (int)$account['is_active'] !== 1) {
            log_message('warning', 'Sesi admin dihentikan: akun {id} tidak ditemukan atau nonaktif.', ['id' => session()->get('userId')]);
            session()->remove(['isLoggedIn', 'userId', 'username', 'fullName', 'role']);
            session()->setFlashdata('error', 'Sesi Anda berakhir karena akun dinonaktifkan atau dihapus. Silakan login kembali.');
            redirect()->to('/login')->send();
            exit;
        }

        if (session()->get('role') !== $account['role']) {
            session()->set('role', $account['role']);
        }

        $settingModel = new SettingModel();
        $this->data['site_name'] = $settingModel->get('site_name') ?? 'SekolahKu';

        $this->data['user'] = [
            'userId' => session()->get('userId'),
            'username' => session()->get('username'),
            'fullName' => session()->get('fullName'),
            // Role is taken from the database, not the session, so revoked permissions
            // take effect immediately (see Users::initController).
            'role' => $account['role'],
        ];
    }

    /**
     * Build a random file name whose extension is derived from the detected
     * image type, never from the client-supplied name.
     *
     * @param int $type An IMAGETYPE_* constant.
     *
     * @return string
     */
    protected function buildSafeName(int $type): string
    {
        $ext = match ($type) {
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp',
            default => 'img',
        };

        return time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    }

    /**
     * Validate a drag-and-drop reorder ID list from a JSON request body.
     *
     * @param mixed $ids Raw `ids` value from the decoded JSON body.
     * @param int $max Maximum number of IDs accepted (anti-abuse).
     *
     * @return int[]|null Sanitized ID list, or null when invalid.
     */
    protected function sanitizeReorderIds(mixed $ids, int $max = 500): ?array
    {
        if (!is_array($ids) || $ids === [] || count($ids) > $max) {
            return null;
        }

        $clean = [];
        foreach ($ids as $v) {
            if (is_int($v)) {
                $id = $v;
            } elseif (is_string($v) && ctype_digit($v)) {
                $id = (int)$v;
            } else {
                return null;
            }
            if ($id <= 0) {
                return null;
            }
            $clean[] = $id;
        }

        // Reject duplicates — the order must be 1:1 with table rows.
        if (count(array_unique($clean)) !== count($clean)) {
            return null;
        }

        return $clean;
    }

    /**
     * Verify that every given ID exists in the model's table.
     *
     * @param Model $model Model instance to check against.
     * @param int[] $ids Sanitized ID list.
     *
     * @return bool
     */
    protected function reorderIdsExist($model, array $ids): bool
    {
        return $model->whereIn('id', $ids)->countAllResults() === count($ids);
    }

    /**
     * Detect dangerous URL schemes (javascript:, data:, vbscript:) to prevent
     * stored XSS via link fields rendered in public views.
     *
     * @param ?string $url The URL to inspect.
     *
     * @return bool
     */
    protected function isDangerousUrl(?string $url): bool
    {
        // Single source of truth: use helper `is_dangerous_url()` (menu_helper)
        // since the public menu renderer also relies on it.
        return is_dangerous_url($url);
    }
}
