<?php

class user {
        private $id;
        private $nome;
        private $telefone;
        private $cpf;
        private $email;
        private $senha;
        private $endereco;
    
        public function __construct($id, $nome, $telefone, $cpf, $email, $senha, $endereco) {
            $this->id = $id;
            $this->nome = $nome;
            $this->telefone = $telefone;
            $this->cpf = $cpf;
            $this->email = $email;
            $this->senha = $senha;
            $this->endereco = $endereco;
        }
    
        public function getId() {
            return $this->id;
        }
        
        public function setId($id) {
            $this->id = $id;
        }        


        public function getNome() {
            return $this->nome;
        }
    
        public function setNome($nome) {
            $this->nome = $nome;
        }
    
        public function getTelefone() {
            return $this->telefone;
        }
    
        public function setTelefone($telefone) {
            $this->telefone = $telefone;
        }
    
        public function getCpf() {
            return $this->cpf;
        }
    
        public function setCpf($cpf) {
            $this->cpf = $cpf;
        }
    
        public function getEmail() {
            return $this->email;
        }
    
        public function setEmail($email) {
            $this->email = $email;
        }
    
        public function getSenha() {
            return $this->senha;
        }
    
        public function setSenha($senha) {
            $this->senha = $senha;
        }
    
        public function getEndereco() {
            return $this->endereco;
        }
    
        public function setEndereco($endereco) {
            $this->endereco = $endereco;
        }
    }
    