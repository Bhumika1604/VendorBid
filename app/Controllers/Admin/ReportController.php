<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AwardModel;
use App\Models\BidModel;
use App\Models\ProjectModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReportController extends BaseController
{
    protected ProjectModel $projectModel;
    protected UserModel $userModel;
    protected BidModel $bidModel;
    protected AwardModel $awardModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->userModel    = new UserModel();
        $this->bidModel     = new BidModel();
        $this->awardModel   = new AwardModel();
    }

    // ===================================================================
    // PROJECTS REPORT
    // ===================================================================

    public function projects()
    {
        $search = trim((string) $this->request->getGet('search'));
        $status = (string) $this->request->getGet('status');

        $filters = ['search' => $search, 'status' => $status];

        $query = $this->projectModel->scopeFilters($filters)->orderBy('created_at', 'DESC');

        $export = (string) $this->request->getGet('export');

        if ($export !== '') {
            $rows = array_map(static fn ($p) => [
                $p['id'],
                $p['title'],
                $p['category'],
                'Rs. ' . number_format((float) $p['budget'], 2),
                $p['location'],
                date('d M Y', strtotime($p['deadline'])),
                ucfirst($p['status']),
                date('d M Y', strtotime($p['created_at'])),
            ], (clone $query)->findAll());

            $headers = ['ID', 'Title', 'Category', 'Budget', 'Location', 'Deadline', 'Status', 'Created On'];

            return $this->export($export, 'Projects Report', $headers, $rows, 'projects_report');
        }

        $projects = $query->paginate(10, 'reports');
        $pager    = $this->projectModel->pager;
        $pager->only(['search', 'status']);

        return view('admin/reports/projects', [
            'title'    => 'Projects Report',
            'projects' => $projects,
            'pager'    => $pager,
            'search'   => $search,
            'status'   => $status,
            'statuses' => ProjectModel::statuses(),
        ]);
    }

    // ===================================================================
    // CONTRACTORS REPORT
    // ===================================================================

    public function contractors()
    {
        $search = trim((string) $this->request->getGet('search'));
        $status = (string) $this->request->getGet('status');

        $query = $this->userModel->where('role', 'contractor');

        if ($search !== '') {
            $query->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('company_name', $search)
                ->groupEnd();
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $query->orderBy('created_at', 'DESC');

        $export = (string) $this->request->getGet('export');

        if ($export !== '') {
            $rows = array_map(static fn ($c) => [
                $c['id'],
                $c['name'],
                $c['company_name'],
                $c['email'],
                $c['phone'],
                ucfirst($c['status']),
                date('d M Y', strtotime($c['created_at'])),
            ], (clone $query)->findAll());

            $headers = ['ID', 'Name', 'Company', 'Email', 'Phone', 'Status', 'Registered On'];

            return $this->export($export, 'Contractors Report', $headers, $rows, 'contractors_report');
        }

        $contractors = $query->paginate(10, 'reports');
        $pager       = $this->userModel->pager;
        $pager->only(['search', 'status']);

        return view('admin/reports/contractors', [
            'title'       => 'Contractors Report',
            'contractors' => $contractors,
            'pager'       => $pager,
            'search'      => $search,
            'status'      => $status,
        ]);
    }

    // ===================================================================
    // BIDS REPORT
    // ===================================================================

    public function bids()
    {
        $search = trim((string) $this->request->getGet('search'));
        $status = (string) $this->request->getGet('status');

        $filters = ['search' => $search, 'status' => $status];

        $query = $this->bidModel->adminBidsQuery($filters)->orderBy('bids.created_at', 'DESC');

        $export = (string) $this->request->getGet('export');

        if ($export !== '') {
            $rows = array_map(static fn ($b) => [
                $b['id'],
                $b['contractor_name'],
                $b['project_title'],
                'Rs. ' . number_format((float) $b['bid_amount'], 2),
                $b['estimated_days'] . ' days',
                ucfirst($b['status']),
                date('d M Y', strtotime($b['created_at'])),
            ], (clone $query)->findAll());

            $headers = ['ID', 'Contractor', 'Project', 'Bid Amount', 'Est. Days', 'Status', 'Submitted On'];

            return $this->export($export, 'Bids Report', $headers, $rows, 'bids_report');
        }

        $bids  = $query->paginate(10, 'reports');
        $pager = $this->bidModel->pager;
        $pager->only(['search', 'status']);

        return view('admin/reports/bids', [
            'title'    => 'Bids Report',
            'bids'     => $bids,
            'pager'    => $pager,
            'search'   => $search,
            'status'   => $status,
            'statuses' => BidModel::statuses(),
        ]);
    }

    // ===================================================================
    // AWARDS REPORT
    // ===================================================================

    public function awards()
    {
        $search = trim((string) $this->request->getGet('search'));

        $query = $this->awardModel->historyQuery(['search' => $search])->orderBy('awards.created_at', 'DESC');

        $export = (string) $this->request->getGet('export');

        if ($export !== '') {
            $rows = array_map(static fn ($a) => [
                $a['id'],
                $a['project_title'],
                $a['contractor_name'],
                $a['contractor_company'],
                'Rs. ' . number_format((float) $a['awarded_amount'], 2),
                date('d M Y', strtotime($a['created_at'])),
            ], (clone $query)->findAll());

            $headers = ['ID', 'Project', 'Contractor', 'Company', 'Awarded Amount', 'Awarded On'];

            return $this->export($export, 'Awards Report', $headers, $rows, 'awards_report');
        }

        $awards = $query->paginate(10, 'reports');
        $pager  = $this->awardModel->pager;
        $pager->only(['search']);

        return view('admin/reports/awards', [
            'title'  => 'Awards Report',
            'awards' => $awards,
            'pager'  => $pager,
            'search' => $search,
        ]);
    }

    // ===================================================================
    // EXPORT HELPERS
    // ===================================================================

    /**
     * Dispatch to the correct export format.
     *
     * @param array<int, string>        $headers
     * @param array<int, array<mixed>>  $rows
     */
    private function export(string $format, string $title, array $headers, array $rows, string $filename): ResponseInterface
    {
        return match ($format) {
            'pdf'   => $this->exportPdf($title, $headers, $rows, $filename),
            'excel' => $this->exportExcel($title, $headers, $rows, $filename),
            default => redirect()->back()->with('error', 'Unknown export format.'),
        };
    }

    /**
     * Export a report to PDF using Dompdf.
     * Requires: composer require dompdf/dompdf
     *
     * @param array<int, string>        $headers
     * @param array<int, array<mixed>>  $rows
     */
    private function exportPdf(string $title, array $headers, array $rows, string $filename): ResponseInterface
    {
        if (! class_exists(\Dompdf\Dompdf::class)) {
            return redirect()->back()->with('error', 'PDF export requires the Dompdf package. Run: composer require dompdf/dompdf');
        }

        $html = view('admin/reports/pdf_template', [
            'title'   => $title,
            'headers' => $headers,
            'rows'    => $rows,
        ]);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '_' . date('Y-m-d') . '.pdf"')
            ->setBody($dompdf->output());
    }

    /**
     * Export a report to an Excel (.xlsx) workbook using PhpSpreadsheet.
     * Requires: composer require phpoffice/phpspreadsheet
     *
     * @param array<int, string>        $headers
     * @param array<int, array<mixed>>  $rows
     */
    private function exportExcel(string $title, array $headers, array $rows, string $filename): ResponseInterface
    {
        if (! class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
            return redirect()->back()->with('error', 'Excel export requires the PhpSpreadsheet package. Run: composer require phpoffice/phpspreadsheet');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr($title, 0, 31));

        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);
        $sheet->fromArray($rows, null, 'A2');

        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '_' . date('Y-m-d') . '.xlsx"')
            ->setBody($content);
    }
}
