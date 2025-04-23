<?php

class Agendamento {

    protected int $id;
    protected int $user_id;
    protected string $dia_semana;
    protected string $horario;
    protected string $status_agendamento;
    protected ?string $observacoes;
    protected DateTime $created_at;
    protected DateTime $updated_at;
    protected ?string $motivo_cancelamento;

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function getDiaSemana(): string {
        return $this->dia_semana;
    }

    public function getHorario(): string {
        return $this->horario;
    }

    public function getStatusAgendamento(): string {
        return $this->status_agendamento;
    }

    public function getObservacoes(): ?string {
        return $this->observacoes;
    }

    public function getCreatedAt(): DateTime {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTime {
        return $this->updated_at;
    }

    public function getMotivoCancelamento(): ?string {
        return $this->motivo_cancelamento;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUserId(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function setDiaSemana(string $dia_semana): void {
        $this->dia_semana = $dia_semana;
    }

    public function setHorario(string $horario): void {
        $this->horario = $horario;
    }

    public function setStatusAgendamento(string $status_agendamento): void {
        $this->status_agendamento = $status_agendamento;
    }

    public function setObservacoes(?string $observacoes): void {
        $this->observacoes = $observacoes;
    }

    public function setCreatedAt(DateTime $created_at): void {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt(DateTime $updated_at): void {
        $this->updated_at = $updated_at;
    }

    public function setMotivoCancelamento(?string $motivo_cancelamento): void {
        $this->motivo_cancelamento = $motivo_cancelamento;
    }
}