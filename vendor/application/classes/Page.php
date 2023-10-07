<?php

//! $tpl:
//* É um objeto da classe RainTPL, responsável por gerenciar os templates
//* e renderizar o conteúdo. Ele será utilizado para carregar e renderizar os templates.

//!Atributo $options:
//* É um array que armazena as opções personalizadas que podem ser passadas
//* para a classe Page. Essas opções podem ser configuradas para controlar o comportamento
//* da página, como exibir ou não o cabeçalho e rodapé, definir dados para serem utilizados 
//*nos templates, entre outros.

//! Atributo $defaults: 
//* É um array que contém as opções padrão da classe Page.
//* Se alguma opção personalizada não for fornecida, esses valores serão utilizados 
//* por padrão. As opções padrão incluem a exibição do cabeçalho e rodapé, além de um
//* array vazio para armazenar dados.

//? HEADER: com as configurações de html5, inclusão de links externos e folhas de estilo
//? NAVBAR: Barra de navegação e menus da aplicação se necessário
//? FOOTER: onde você definirá o rodapé da aplicação se necessário
//? FOOTER CONFIG: com as chamadas de links externos e arquivos de javascript
//? DATA: array vazio para passar dados dinamicos vindos do banco de dados para o front-end

//!OBS:
//* se não desejar carregar alguma dessas opções, setar o parametro 
//* como false na chamada da classe Page na configuração da 
//* rota no arquivo index.php

namespace application;

use Rain\Tpl;

class Page {

    private $tpl;
    private $options = [];
    private $defaults = [ //* Estas são opções para carregamento no template, se não desejar carregar alguma delas é só atribuir como false na chamada da classe de configuração da rota no arquivo index.php
        "header"=>true,
        "navbar"=>true,
        "footer"=>true,
        "footerConfig"=>true,
        "data"=>[]
    ];

    /*
     * Método construtor da classe Page.
     * Primeiro parametro '$opts' é para as Opções personalizadas no template
     * Segundo parametro '$tpl_dir' é para o caminho do Diretório dos templates
    */

    public function __construct($opts = array(), $tpl_dir = "views/"){

        //*Mescla as opções passadas com as opções padrões
        $this->options = array_merge($this->defaults, $opts);

        //* Configuração do RainTpl
        //? O caminho para o diretorio 'views' e 'views-cache' pode mudar de windows para linux
        //? Linux : views/ e ../views-cache
        //? Windows : views/ e ./views-cache
        $config = array(
            "tpl_dir"=> $tpl_dir,
            "cache_dir"=> "../views-cache/",
            "debug"=>true
        );

        //* Configura o RainTpl com as opções fornecidas no array acima
        Tpl::configure($config);

        //* Instancia o objeto RainTpl
        $this->tpl = new Tpl;

        //* Define os dados dinamicos a serem utilizados no template
        $this->setData($this->options["data"]);

        //* Renderiza o cabeçalho, se a opção "header" estiver habilitada
        if($this->options["header"] === true) {
            $this->tpl->draw("header");
        }

        //* Renderiza a navbar se a opção "navbar" estiver habilitada
        if($this->options["navbar"] === true){
            $this->tpl->draw("navbar");
        }   
    
    }

    /*
     * Define os dados a serem atribuídos no template.
     * parametro $data é um array vazio para os Dados a serem atribuídos
    */
    private function setData($data = array()){

        //* Define os dados a serem atribuídos no template
        foreach($data as $key => $value){
            $this->tpl->assign($key, $value);
        }
    }

    /*
     * Define o template a ser utilizado e renderiza o conteúdo.
     * 1° parametro $name é o Nome do template
     * 2° parametro $data são os Dados a serem atribuídos no template
     * 3° parametro $returnHTML Indica se deve retornar o HTML ou imprimir na saída
    */
    public function renderPage($name, $data = array(), $returnHTML = false){

        //* Define os dados a serem utilizados no template
        $this->setData($data);   
        
        //* Renderiza o template especificado e retorna o HTML ou imprime na saída
        return $this->tpl->draw($name, $returnHTML);
    }

    //* Método destruct da classe Page.
    public function __destruct(){
     
        //*Desenha o rodapé, se a opção "footer" estiver habilitada
        if ($this->options['footer'] === true) $this->tpl->draw("footer");

        //*Renderiza os links de dependencias e javascript se a opção "footerConfig" estiver habilitada
        if($this->options["footerConfig"] === true) $this->tpl->draw("footerConfig");
    }


    


    
}