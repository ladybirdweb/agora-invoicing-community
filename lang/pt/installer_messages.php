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
    'extension_not_enabled' => 'Não Habilitado: Para habilitar isso, instale a extensão em seu servidor e atualize :php_ini_file para habilitar :extensionName. <a href=":url" target="_blank">Como instalar extensões PHP no meu servidor?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'DESLIGADO (Se você estiver usando Apache, verifique se <var><strong>AllowOverride</strong></var> está definido como <var><strong>All</strong></var> na configuração do Apache)',
    'rewrite_engine' => 'Motor de Reescrita',
    'user_url' => 'URL amigável ao usuário',

    'host' => 'Host',
    'host_tooltip' => 'Se seu MySQL estiver instalado no mesmo servidor que o Agora Invoicing, deixe como localhost',
    'database_name_label' => 'Nome do banco de dados',
    'mysql_port_label' => 'Número da porta MySQL',
    'mysql_port_tooltip' => 'Número da porta na qual seu servidor MySQL está escutando. Por padrão, é 3306',
    'username' => 'Nome de usuário',
    'password_label' => 'Senha',
    'test_prerequisites_message' => 'Este teste verificará os requisitos necessários para instalar o Agora Invoicing',
    'previous' => 'Anterior',

    'sign_up_as_admin' => 'Inscrever-se como Admin',
    'first_name' => 'Nome',
    'first_name_required' => 'O nome é obrigatório',
    'last_name' => 'Sobrenome',
    'last_name_required' => 'O sobrenome é obrigatório',
    'username_info' => 'O nome de usuário pode conter apenas caracteres alfanuméricos, espaços, sublinhados, hífens, pontos e o símbolo @.',
    'email' => 'E-mail',
    'email_required' => 'O e-mail do usuário é obrigatório',
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
    'final_setup' => 'Sua aplicação Agora Invoicing está pronta!',
    'installation_complete' => 'Tudo certo, amigo! Você completou a instalação.',

    // Saiba Mais
    'learn_more' => 'Saiba Mais',
    'knowledge_base' => 'Base de Conhecimento',
    'email_support' => 'Suporte por E-mail',

    // Próximo Passo
    'next_step' => 'Próximo Passo',
    'login_button' => 'Entrar no Faturamento',

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
    'database_not_empty' => 'A instalação do Agora Invoicing requer um banco de dados vazio, seu banco de dados já possui tabelas e dados.',
    'mysql_version_required' => 'Recomendamos atualizar para pelo menos MySQL 5.6 ou MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Conexão com o banco de dados malsucedida.',
    'connected_as' => 'Conectado ao banco de dados como',
    'failed_connection' => 'Falha ao conectar ao banco de dados.',

];
