<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Report;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportsSpreadsheetController extends Controller
{
  private $token;

  /*
    >> Create's a spreadsheet
  */

  public function create()
  {
    $core = new CoreController;
    $this->user = Auth::user();

    $this->error = 0;

    $this->get_reports = Report::orderBy('id', 'desc');

    if($this->get_reports->count() > 0)
    {
      $this->reports = $this->get_reports->get();

      $this->spreadsheet = new Spreadsheet();
      $this->sheet = $this->spreadsheet->getActiveSheet();

      $this->spreadsheet->getProperties()
                        ->setCreator($this->user->username)
                        ->setTitle("Report List, Generated on: ".$core->dt_format());

      $this->table_start = 2;

      // Headers
      $this->sheet->setCellValue('B'.$this->table_start, 'ID');
      $this->sheet->setCellValue('C'.$this->table_start, 'Reported');
      $this->sheet->setCellValue('D'.$this->table_start, 'Reporter');
      $this->sheet->setCellValue('E'.$this->table_start, 'Reason');
      $this->sheet->setCellValue('F'.$this->table_start, 'World');
      $this->sheet->setCellValue('G'.$this->table_start, 'X, Y, Z');
      $this->sheet->setCellValue('H'.$this->table_start, 'Section');
      $this->sheet->setCellValue('I'.$this->table_start, 'Sub-Section');
      $this->sheet->setCellValue('J'.$this->table_start, 'Resolving');
      $this->sheet->setCellValue('K'.$this->table_start, 'Open');
      $this->sheet->setCellValue('L'.$this->table_start, 'Ticket Manager');
      $this->sheet->setCellValue('M'.$this->table_start, 'How Resolved');
      $this->sheet->setCellValue('N'.$this->table_start, 'Server Name');

      // Values
      foreach($this->reports as $key => $item)
      {
        $this->table_id = $this->table_start + ($key + 1);

        $this->sheet->setCellValue('B'.$this->table_id, $item->id);
        $this->sheet->setCellValue('C'.$this->table_id, $item->reported);
        $this->sheet->setCellValue('D'.$this->table_id, $item->reporter);
        $this->sheet->setCellValue('E'.$this->table_id, $item->reason);
        $this->sheet->setCellValue('F'.$this->table_id, $item->world);
        $this->sheet->setCellValue('G'.$this->table_id, round($item->x).', '.round($item->y).', '.round($item->z));
        $this->sheet->setCellValue('H'.$this->table_id, $item->section);
        $this->sheet->setCellValue('I'.$this->table_id, $item->subSection);
        $this->sheet->setCellValue('J'.$this->table_id, $item->resolving == 1 ? 'Yes' : 'No');
        $this->sheet->setCellValue('K'.$this->table_id, $item->open == 1 ? 'Yes' : 'No');
        $this->sheet->setCellValue('L'.$this->table_id, $item->ticketManager != "none" ? $item->ticketManager : 'N/A');
        $this->sheet->setCellValue('M'.$this->table_id, $item->howResolved != "none" ? $item->howResolved : 'N/A');
        $this->sheet->setCellValue('N'.$this->table_id, $item->serverName);
      }

      // Styling
      $this->sheet->getColumnDimension('C')->setWidth(19);
      $this->sheet->getColumnDimension('D')->setWidth(16);
      $this->sheet->getColumnDimension('E')->setWidth(26);
      $this->sheet->getColumnDimension('F')->setWidth(10);
      $this->sheet->getColumnDimension('G')->setWidth(16);
      $this->sheet->getColumnDimension('H')->setWidth(18);
      $this->sheet->getColumnDimension('I')->setWidth(16);
      $this->sheet->getColumnDimension('J')->setWidth(16);
      $this->sheet->getColumnDimension('K')->setWidth(16);
      $this->sheet->getColumnDimension('L')->setWidth(19);
      $this->sheet->getColumnDimension('M')->setWidth(19);
      $this->sheet->getColumnDimension('N')->setWidth(15);

      $this->sheet->getStyle('B'.$this->table_start.':N'.($this->get_reports->count() + $this->table_start))->applyFromArray([
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
          'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
          'right' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
          'bottom' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ],
          'left' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          ]
        ]
      ]);

      $this->download_id = $core->generateID();

      $this->writer = new Xlsx($this->spreadsheet);

      $this->writer->save(storage_path('app/private/spreadsheets/'.$this->download_id.'.xlsx'));

      return response()->json([
        'error' => 0,
        'download_link' => url('panel/report/spreadsheet/download/'.$this->download_id)
      ]);
    }
    else
    {
      return response()->json([
        'error' => 2,
        'message' => __('sentences.report-spread-no-reports')
      ]);
    }
  }

  /*
    >> Download's a spreadsheet
  */

  public function download($token)
  {
    $this->core = new CoreController;
    $this->user = Auth::user();

    $this->token = mb_strtolower($token);

    // Generating the spreadsheet list
    if(empty($this->token))
    {
      $this->error = 1;
    }
    else if(!Storage::disk('private')->exists('spreadsheets/'.$this->token.'.xlsx'))
    {
      $this->error = 1;
    }
    else
    {
      $this->error = 0;
    }

    if($this->error != 1)
    {
      return response()->download(storage_path('app/private/spreadsheets/'.$this->token.'.xlsx'));
    }
    else
    {
      return abort(404);
    }
  }
}
