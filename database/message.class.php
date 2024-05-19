<?php
declare(strict_types = 1);

class Mensagem {
    private $id;
    private $remetente;
    private $destinatario;
    private $assunto;
    private $conteudo;
    private $data;

    public function __construct($id, $remetente, $destinatario, $assunto, $conteudo, $data) {
        $this->id = $id;
        $this->remetente = $remetente;
        $this->destinatario = $destinatario;
        $this->assunto = $assunto;
        $this->conteudo = $conteudo;
        $this->data = $data;
    }

    // Getters e Setters

    public function getId() {
        return $this->id;
    }

    public function getRemetente() {
        return $this->remetente;
    }

    public function setRemetente($remetente) {
        $this->remetente = $remetente;
    }

    public function getDestinatario() {
        return $this->destinatario;
    }

    public function setDestinatario($destinatario) {
        $this->destinatario = $destinatario;
    }

    public function getAssunto() {
        return $this->assunto;
    }

    public function setAssunto($assunto) {
        $this->assunto = $assunto;
    }

    public function getConteudo() {
        return $this->conteudo;
    }

    public function setConteudo($conteudo) {
        $this->conteudo = $conteudo;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }
}

?>