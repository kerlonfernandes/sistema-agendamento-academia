<?php

use Midspace\Operations\Operations;

require_once("classes/Operations.class.php");
require_once("models/UserModel.php");
require_once("models/InternModel.php");
require_once("models/AgendaModel.php");

class RelatoriosController extends Base
{
    public UserModel $userModel;
    private int $userId;
    public InternModel $internModel;
    public AgendaModel $agendaModel;

    public function __construct()
    {
        parent::__construct();
        $this->isAdminAuth();
        $this->userModel = new UserModel();
        $this->userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
        $this->internModel = new InternModel();
        $this->agendaModel = new AgendaModel();

    }

    public function index()
    {
    }

    public function gerar_relatorio() 
    {
        $get = Get();
    
        if (empty($get->horario)) {
            $_SESSION['alert_message'] = [
                'type' => 'warning',
                'title' => 'Não foi possível gerar um relatório!',
                'message' => 'O Horário não pode estar vazio',
                'dismissible' => true,
            ];
        }
    
        try {
            require_once 'vendor/autoload.php';
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $titulo = 'Relatório de Agendamentos';
            
            $meses = [
                '1' => 'Janeiro', '2' => 'Fevereiro', '3' => 'Março', 
                '4' => 'Abril', '5' => 'Maio', '6' => 'Junho',
                '7' => 'Julho', '8' => 'Agosto', '9' => 'Setembro',
                '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro', 'all' => 'Todos os meses'
            ];
            
            if (!empty($get->start_date) && !empty($get->end_date)) {
                $titulo .= ' - Período: ' . date('d/m/Y', strtotime($get->start_date)) . ' a ' . date('d/m/Y', strtotime($get->end_date));
            } else {
                // Filtro por mês/ano
                $mesSelecionado = $get->month ?? 'all';
                $tituloMes = $meses[$mesSelecionado] ?? 'Todos os meses';
                $titulo .= ' - ' . $tituloMes;
                
                if (!empty($get->year) && $mesSelecionado !== 'all') {
                    $titulo .= ' de ' . $get->year;
                }
            }
            
            $sheet->setCellValue('A1', $titulo);
            $sheet->mergeCells('A1:G1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            
            $headers = [
                'Nome', 
                'Telefone', 
                'Dia da Semana', 
                'Horário Início', 
                'Horário Fim', 
                'Data do Agendamento', 
                'Status'
            ];
            
            $sheet->fromArray($headers, null, 'A3');
            
            $row = 4;
            $horarios = is_array($get->horario) ? $get->horario : [$get->horario];
            
            foreach($horarios as $horario) {
                $query = 'SELECT 
                         users.nome,
                         users.telefone,
                         horarios.dia_semana,
                         horarios.horario_inicio,
                         horarios.horario_fim,
                         horarios.created_at AS "Dia do Agendamento",
                         agendamentos_clientes.status_agendamento 
                         FROM agendamentos_clientes 
                         LEFT JOIN horarios ON horarios.id = agendamentos_clientes.horario_id
                         RIGHT JOIN users ON users.id = agendamentos_clientes.user_id
                         WHERE horario_id = :horario_id AND users.status = 1';
                
                $params = [':horario_id' => $horario];
                
                if (!empty($get->start_date) && !empty($get->end_date)) {
                    $query .= ' AND DATE(horarios.created_at) BETWEEN :start_date AND :end_date';
                    $params[':start_date'] = $get->start_date;
                    $params[':end_date'] = $get->end_date;
                } elseif (!empty($get->month) && $get->month !== 'all') {
                    $query .= ' AND MONTH(horarios.created_at) = :month';
                    $params[':month'] = $get->month;
                    
                    if (!empty($get->year)) {
                        $query .= ' AND YEAR(horarios.created_at) = :year';
                        $params[':year'] = $get->year;
                    }
                }
                
                $result = $this->agendaModel->database->execute_query($query, $params);
                
                if ($result->status === 'success' && !empty($result->results)) {
                    foreach ($result->results as $agendamento) {
                        $sheet->setCellValue('A' . $row, $agendamento->nome);
                        $sheet->setCellValue('B' . $row, $agendamento->telefone);
                        $sheet->setCellValue('C' . $row, $agendamento->dia_semana);
                        $sheet->setCellValue('D' . $row, $agendamento->horario_inicio);
                        $sheet->setCellValue('E' . $row, $agendamento->horario_fim);
                        
                        $dataFormatada = date('d/m/Y H:i', strtotime($agendamento->{'Dia do Agendamento'}));
                        $sheet->setCellValue('F' . $row, $dataFormatada);
                        
                        $sheet->setCellValue('G' . $row, $agendamento->status_agendamento);
                        $row++;
                    }
                }
            }
            
            // Se não houver resultados, adicionar mensagem
            if ($row == 4) {
                $sheet->setCellValue('A4', 'Nenhum agendamento encontrado para os filtros selecionados');
                $sheet->mergeCells('A4:G4');
            }
            
            // Formatação da planilha
            $sheet->getStyle('A3:G3')->getFont()->setBold(true);
            $sheet->getStyle('A3:G3')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFD3D3D3');
            
            // Auto dimensionar as colunas
            foreach (range('A', 'G') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Adicionar filtros se houver dados
            if ($row > 5) {
                $sheet->setAutoFilter('A3:G' . ($row-1));
            }
            
            $filename = 'relatorio_agendamentos_' . date('Ymd_His');
            
            if (!empty($get->start_date) && !empty($get->end_date)) {
                $filename .= '_' . str_replace('-', '', $get->start_date) . '_a_' . str_replace('-', '', $get->end_date);
            } elseif (!empty($get->month) && $get->month !== 'all') {
                $filename .= '_' . $get->month;
                if (!empty($get->year)) {
                    $filename .= '_' . $get->year;
                }
            }
            
            $filename .= '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // Gerar e enviar o arquivo
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
            
        } catch (Exception $e) {

            $_SESSION['alert_message'] = [
                'type' => 'warning',
                'title' => 'Não foi possível gerar um relatório!',
                'message' => "Erro ao gerar relatório: " . $e->getMessage(),
                'dismissible' => true,
            ];
        }
    }
}