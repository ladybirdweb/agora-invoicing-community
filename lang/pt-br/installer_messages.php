<?php

return [

    'title' => 'Instalador do Agora Invoicing',
    'probe' => 'Provas do Agora Invoicing',
    'magic_phrase' => 'Qual é a frase mágica',
    'server_requirements' => 'Requisitos do Servidor',
    'database_setup' => 'Configuração do Banco de Dados',
    'getting_started' => 'Introdução',
    'final' => 'Final',
    'directory' => 'Diretório',
    'permissions' => 'Permissões',
    'requisites' => 'Requisitos',
    'status' => 'Status',
    'php_extensions' => 'Extensões PHP',
    'not_enabled' => 'Não Habilitado',
    'extension_not_enabled' => 'Não Habilitado: Para habilitar isso, instale a extensão no seu servidor e atualize :php_ini_file para habilitar :extensionName. <a href=":url" target="_blank">Como instalar extensões PHP no meu servidor?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'DESLIGADO (Se você estiver usando o Apache, certifique-se de que <var><strong>AllowOverride</strong></var> esteja definido como <var><strong>All</strong></var> na configuração do Apache)',
    'rewrite_engine' => 'Motor de Reescrita',
    'user_url' => 'URL Amigável ao Usuário',

    'host' => 'Host',
    'host_tooltip' => 'Se seu MySQL estiver instalado no mesmo servidor que o Agora Invoicing, deixe como localhost',
    'database_name_label' => 'Nome do Banco de Dados',
    'mysql_port_label' => 'Número da Porta MySQL',
    'mysql_port_tooltip' => 'Número da porta em que seu servidor MySQL está escutando. Por padrão, é 3306',
    'username' => 'Nome de Usuário',
    'password_label' => 'Senha',
    'test_prerequisites_message' => 'Este teste verificará os requisitos necessários para instalar o Agora Invoicing',
    'previous' => 'Anterior',

    'sign_up_as_admin' => 'Cadastrar-se como Administrador',
    'first_name' => 'Primeiro Nome',
    'first_name_required' => 'O Primeiro Nome é obrigatório',
    'last_name' => 'Sobrenome',
    'last_name_required' => 'O Sobrenome é obrigatório',
    'username_info' => 'O Nome de Usuário pode ter apenas caracteres alfanuméricos, espaços, sublinhados, hífens, pontos e o símbolo @.',
    'email' => 'Email',
    'email_required' => 'O email do usuário é obrigatório',
    'password_required' => 'A senha é obrigatória',
    'confirm_password' => 'Confirmar Senha',
    'confirm_password_required' => 'A confirmação da senha é obrigatória',
    'password_requirements' => 'Sua senha deve ter:',
    'password_requirements_list' => [
        'Entre 8 e 16 caracteres',
        'Caracteres maiúsculos (A-Z)',
        'Caracteres minúsculos (a-z)',
        'Números (0-9)',
        'Caracteres especiais (~*!@$#%_+.?:,{ })',
    ],

    // Informações do Sistema
    'system_information' => 'Informações do Sistema',
    'environment' => 'Ambiente',
    'environment_required' => 'O ambiente é obrigatório',
    'production' => 'Produção',
    'development' => 'Desenvolvimento',
    'testing' => 'Teste',
    'cache_driver' => 'Driver de Cache',
    'cache_driver_required' => 'O driver de cache é obrigatório',
    'file' => 'Arquivo',
    'redis' => 'Redis',
    'password' => 'Senha',

    // Configuração do Redis
    'redis_setup' => 'Configuração do Redis',
    'redis_host' => 'Host do Redis',
    'redis_port' => 'Porta do Redis',
    'redis_password' => 'Senha do Redis',

    // Botões
    'continue' => 'Continuar',

    // Configuração Final
    'final_setup' => 'Sua Aplicação Agora Invoicing está Pronta!',
    'installation_complete' => 'Tudo certo, sparky! Você concluiu a instalação.',

    // Saiba Mais
    'learn_more' => 'Saiba Mais',
    'knowledge_base' => 'Base de Conhecimento',
    'email_support' => 'Suporte por Email',

    // Próximo Passo
    'next_step' => 'Próximo Passo',
    'login_button' => 'Login no Billing',

    'pre_migration_success' => 'A pré-migração foi testada com sucesso',
    'migrating_tables' => 'Migrando tabelas no banco de dados',
    'db_connection_error' => 'A conexão com o banco de dados não foi atualizada.',
    'database_setup_success' => 'O banco de dados foi configurado com sucesso.',
    'env_file_created' => 'O arquivo de configuração do ambiente foi criado com sucesso',
    'pre_migration_test' => 'Executando teste de pré-migração',

    'redis_host_required' => 'O host do Redis é obrigatório.',
    'redis_password_required' => 'A senha do Redis é obrigatória.',
    'redis_port_required' => 'A porta do Redis é obrigatória.',
    'password_regex' => 'A senha deve conter pelo menos 8 caracteres, uma letra maiúscula, uma letra minúscula, um número e um caractere especial.',
    'setup_completed' => 'Configuração concluída com sucesso!',

    'database' => 'Banco de Dados',
    'selected' => 'Selecionado',
    'mysql_version_is' => 'A versão do MySQL é',
    'database_empty' => 'O banco de dados está vazio',
    'database_not_empty' => 'A instalação do Agora Invoicing requer um banco de dados vazio, seu banco de dados já possui tabelas e dados nele.',
    'mysql_version_required' => 'Recomendamos atualizar para pelo menos MySQL 5.6 ou MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Conexão com o banco de dados malsucedida.',
    'connected_as' => 'Conectado ao banco de dados como',
    'failed_connection' => 'Falha ao conectar ao banco de dados.',

];
