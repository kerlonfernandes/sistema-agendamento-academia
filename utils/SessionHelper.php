<?php

class SessionHelper
{
    /**
     * Verifica se as chaves de sessão existem e são válidas.
     *
     * @param array $keysWithMessages Associativo de chaves de sessão e mensagens personalizadas.
     * @return void
     */
    public function verifySession(array $keysWithMessages): void
    {
        foreach ($keysWithMessages as $key => $message) {
            if (!isset($_SESSION[$key]) || empty($_SESSION[$key])) {
                echo json_encode([
                    "status" => "error",
                    "message" => $message,
                ]);
                exit;
            }
        }
    }
}
