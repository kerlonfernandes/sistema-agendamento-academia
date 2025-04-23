<?php

class User {

    protected int $id;
    protected string $name;
    protected string $telefone;
    protected string $email;
    protected string $vinculo;
    
    
    public function __construct(
        string $name,
        string $telefone,
        string $email,
        string $vinculo
    ) 
    {
        $this->name = $name;
        $this->telefone = formatarTelefone($telefone);
        $this->email = $email;
        $this->vinculo = $vinculo;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getTelefone(): string {
        return $this->telefone;
    }

    public function setTelefone(string $telefone): void {
        $this->telefone = formatarTelefone($telefone);
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getVinculo(): string {
        return $this->vinculo;
    }

    public function setVinculo(string $vinculo): void {
        $this->vinculo = $vinculo;
    }

}