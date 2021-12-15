<?php
class Form
{
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("disciplina", "");
    $form->set("materia", "");
    $form->set("professor", "");
    $retorno["msg"] = $form->saida();
    return $retorno;
  }

  public function salvar()
  {
    if (isset($_POST["disciplina"]) && isset($_POST["materia"]) && isset($_POST["professor"])) {
      try {
        $conexao = Transaction::get();
        $disciplina = $conexao->quote($_POST["disciplina"]);
        $materia = $conexao->quote($_POST["materia"]);
        $professor = $conexao->quote($_POST["professor"]);
        $crud = new Crud();
        if (empty($_POST["id"])) {
          $retorno = $crud->insert(
            "avaliacao",
            "disciplina,materia,professor",
            "{$disciplina},{$materia},{$professor}"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $retorno = $crud->update(
            "avaliacao",
            "disciplina={$disciplina}, materia={$materia}, professor={$professor}",
            "id={$id}"
          );
        }
      } catch (Exception $e) {
        $retorno["msg"] = "Ocorreu um erro! " . $e->getMessage();
        $retorno["erro"] = TRUE;
      }
    } else {
      $retorno["msg"] = "Preencha todos os campos! ";
      $retorno["erro"] = TRUE;
    }
    return $retorno;
  }

  public function __destruct()
  {
    Transaction::close();
  }
}
