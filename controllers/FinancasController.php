<?php
use Midspace\Operations\Operations;

require_once("classes/Operations.class.php");
require_once("models/UserModel.php");
require_once("models/InternModel.php");
require_once("models/AgendaModel.php");

class FinancasController extends Base {

    public UserModel $userModel;
    public InternModel $internModel;
    public AgendaModel $agendaModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->internModel = new InternModel();
        $this->agendaModel = new AgendaModel();
    }
	public function index() {

		$this->view('admin/financeiro', [
			'title' => 'Finanças'
		]);
	}

    public function configuracoes() {
       
    }


    public function init_config() {

        $config = $this->internModel->get_configuracoes()->results[0];
        
        $financeiro = $config->financeiro;

        $vinculos = $config->vinculos;

        $vinculos = json_decode($vinculos, true);

        $array_vinculos_valores = [];

        foreach($vinculos as $vinculo) {
            // definindo os valores padroes
            if($vinculo == "Professor") {
                $array_vinculos_valores[$vinculo] = doubleval(60.00);
            }
            if($vinculo == "Tecnico administrativo") {
                $array_vinculos_valores[$vinculo] = doubleval(50.00);
            }
            if($vinculo == "Estudante") {
                $array_vinculos_valores[$vinculo] = doubleval(70.00);
            }
        }

        $vinculo_json = json_encode($array_vinculos_valores, JSON_PRESERVE_ZERO_FRACTION);

        $config->vinculos = $vinculo_json;

        if(empty($financeiro)) {
            $this->internModel->updateTable('configuracoes', ['financeiro' => $vinculo_json], ['id' => 1]);
        }
    }

    public function registrar_pagamento() {

            $post = Post();
            $user_id = $post->user_id;

            $res = $this->internModel->insertInto('pendencias_financeiras', [
                'user_id',
                'valor',
                'status',
                'data_vencimento',
                'data_pagamento',
                'forma_pagamento',
                'observacoes'
            ], [
                $user_id,
                $post->valor,
                $post->status,
                $post->data_vencimento,
                $post->data_pagamento,
                $post->forma_pagamento,
                $post->observacao
            ]);
            

            if($res->affected_rows > 0) {
                $_SESSION['alert_message'] = [
                    'type'        => 'success', // pode ser: success, danger, warning, info, etc.
                    'title'       => 'Sucesso!',
                    'message'     => 'Pagamento registrado com sucesso!',
                    'dismissible' => true // se pode ser fechado ou não
                ];  

                $this->helpers->redirect(SITE . "/admin/usuario/detalhes/" . $user_id);
            } else {
                $_SESSION['alert_message'] = [
                    'type'        => 'error', // pode ser: success, danger, warning, info, etc.
                    'title'       => 'Erro!',
                    'message'     => 'Erro ao registrar pagamento',
                    'dismissible' => true // se pode ser fechado ou não
                ];

                $this->helpers->redirect(SITE . "/admin/usuario/detalhes/" . $user_id);
            }

    }

    public function deletar_pagamento() {
        $get = Get();
        $id = $get->id;
        $user_id = $get->user_id;
        $res = $this->internModel->deleteFrom('pendencias_financeiras', ['id' => $id]);

        if($res->affected_rows > 0) {
            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'message' => 'Pagamento deletado com sucesso!',
                'dismissible' => true
            ];
        }
        else {
                $_SESSION['alert_message'] = [
                    'type' => 'error',
                'title' => 'Erro!',
                'message' => 'Erro ao deletar pagamento',
                'dismissible' => true
            ];
        }

        $this->helpers->redirect(SITE . "/admin/usuario/detalhes/" . $user_id);
    }

    public function editar_pagamento() {
        $post = Post();
        $id = $post->pagamento_id;
        $user_id = $post->user_id;
        $back_url = $post->back_url;
    
        $res = $this->internModel->updateTable('pendencias_financeiras', [
            'valor' => $post->valor,
            'data_vencimento' => $post->data_vencimento,
            'data_pagamento' => $post->data_pagamento,
            'forma_pagamento' => $post->forma_pagamento,
            'status' => $post->status,
            'observacoes' => $post->observacao
        ], ['id' => $id]);

        if($res->affected_rows > 0) {
            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'message' => 'Pagamento editado com sucesso!',
                'dismissible' => true
            ];
        }
        else {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'message' => 'Erro ao editar pagamento',
                'dismissible' => true
            ];
        }

        $this->helpers->redirect($back_url);
    }   
}

