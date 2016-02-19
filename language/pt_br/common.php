<?php
/**
*
* @package phpBB Extension - phpBB Paypal Donation
* @copyright (c) 2015 marcosfpo
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
* @Author Stoker - http://www.phpbb3bbcodes.com
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'DONATEINDEX'					=> 'Torne-se um colaborador!',
	
	'VIEWING_DONATE'				=> 'Visualizar página de colaboração',
	
	'ACP_DONATION_MOD'				=> 'Extensão Donation',
	
	'DONATION_SAVED'				=> 'Configurações de Donation gravadas',
	
	'DONATION_VERSION'				=> 'Versão',
	
	'DONATION_CONFIG'				=> 'Configuração',
	'DONATION_SETTINGS'				=> 'Configurações gerais de Donation',
	
	'DONATION_ENABLE'				=> 'Habilitar extensão Donation',
	'DONATION_ENABLE_EXPLAIN'			=> 'Habilitar ou desabilitar a extensão Donation.',
	
	'DONATION_INDEX_ENABLE'				=> 'Exibir as estatísticas de colaborações no índice',
	'DONATION_INDEX_ENABLE_EXPLAIN'			=> 'Habilite, se deseja exibir as estatísticas de colaborações no índice.',
	
	'DONATION_INDEX_TOP'				=> 'Exibir estatísticas de colaborações no alto',
	'DONATION_INDEX_TOP_EXPLAIN'			=> 'Habilite, se deseja exibir as estatísticas de colaborações no alto do fórum.',
	
	'DONATION_INDEX_BOTTOM'				=> 'Exibir estatísticas de colaborações embaixo',
	'DONATION_INDEX_BOTTOM_EXPLAIN'			=> 'Habilite, se deseja exibir as estatísticas de colaborações embaixo do fórum.',
	
	'DONATION_EMAIL_PAYPAL'				=> 'Endereço de email do PayPal',
	'DONATION_EMAIL_PAYPAL_EXPLAIN'			=> 'Entre com o endereço de email da conta a ser creditada no PayPal.',
	
	'DONATION_EMAIL_PAGSEGURO'			=> 'Endereço de email do PagSeguro',
	'DONATION_EMAIL_PAGSEGURO_EXPLAIN'		=> 'Entre com o endereço de email da conta a ser creditada no PagSeguro.',
	
	'DONATION_STATS_SETTINGS'			=> 'Configurações das estatísticas de colaborações',
	
	'DONATION_ACHIEVEMENT_ENABLE'			=> 'Habilitar colaborações obtidas',
	'DONATION_ACHIEVEMENT_ENABLE_EXPLAIN'		=> 'Habilite a exibição das colaborações obtidas, se deseja exibi-las.',
	
	'DONATION_ACHIEVEMENT'				=> 'Colaborações obtidas',
	'DONATION_ACHIEVEMENT_EXPLAIN'			=> 'Valor das colaborações obtidas.',
	
	'DONATION_GOAL_ENABLE'				=> 'Habilitar meta para colaborações',
	'DONATION_GOAL_ENABLE_EXPLAIN'			=> 'Habilite a meta para colaborações, se deseja exibi-la.',
	
	'DONATION_GOAL'					=> 'Meta para colaborações',
	'DONATION_GOAL_EXPLAIN'				=> 'Valor que se deseja obter em colaborações.',
	
	'DONATION_GOAL_CURRENCY_ENABLE'			=> 'Habilitar moeda para meta de colaborações',
	'DONATION_GOAL_CURRENCY_ENABLE_EXPLAIN'		=> 'Habilite a moeda para meta de colaborações, se deseja exibi-la.',
	
	'DONATION_GOAL_CURRENCY'			=> 'Moeda da meta para colaborações',
	'DONATION_GOAL_CURRENCY_EXPLAIN'		=> 'Moeda a ser usada para colaborações obtidas e meta para colaborações.',
	
	'DONATION_AMOUNT'				=> 'Valor da colaboração',
	'DONATION_AMOUNT_EXPLAIN'			=> 'Valor que o colaborador contribuiu.',
	
	'DONATION_BODY_SETTINGS'			=> 'Configuração da página de colaboração',
	
	'DONATION_PM_SETTINGS'				=> 'Texto da mensagem privada de alerta',

	'DONATION_PM'                                   => 'Texto da MP',
	'DONATION_PM_EXPLAIN'			        => 'Texto da mensagem privada a ser enviada ao colaborador sobre o vencimento próximo.<br />Use as seguintes tags, se desejar: {USER}: destinatário; {LAST}: data da última colaboração; {EXPIRATION}: data de vencimento; {DAYS_TO_EXPIRE}: número de dias até o vencimento; {OLD_AMOUNT}: valor da última colaboração;  {NEW_AMOUNT}: valor atual da colaboração.<br /><br />BB Code está disponível.',

	'DONATION_PM_SUBJECT'				=> 'Assunto da MP',
	'DONATION_PM_SUBJECT_EXPLAIN'			=> 'Assunto da MP de alerta para vencimento da colaboração.',

	'DONATION_EXPIRED_PM'				=> 'MP para colaborações vencidas',
	'DONATION_EXPIRED_PM_EXPLAIN'			=> 'Texto da mensagem privada a ser enviada quando a colaboração venceu.<br />Use as seguintes tags, se desejar: {USER}: destinatário; {LAST}: data da última colaboração; {EXPIRATION}: data de vencimento; {DAYS_TO_EXPIRE}: número de dias até o vencimento; {OLD_AMOUNT}: valor da última colaboração;  {NEW_AMOUNT}: valor atual da colaboração.<br /><br />BB Code está disponível.',

	'DONATION_EXPIRED_PM_SUBJECT'			=> 'Assunto da MP de colaboração vencida',
	'DONATION_EXPIRED_PM_SUBJECT_EXPLAIN'		=> 'Assunto da mensagem privada que será enviada quando a colaboração venceu.',
	
	'DONATION_BODY'					=> 'Texto da página de colaboração',
	'DONATION_BODY_EXPLAIN'				=> 'Entre com um texto para ser exibido na página de colaboração.<br /><br />HTML está disponível.',

	'DONATION_SUCCESS_SETTINGS'			=> 'Configuração da página de colaboração bem sucedida',

	'DONATION_SUCCESS'				=> 'Texto da página de colaboração bem sucedida',
	'DONATION_SUCCESS_EXPLAIN'			=> 'Entre com um texto a ser exibido caso a colaboração seja bem sucedida.<br />Esta página é exibida após o sucesso da colaboração.<br /><br />HTML está disponível.',

	'DONATION_CANCEL_SETTINGS'			=> 'Configuração da página de colboração cancelada',

	'DONATION_CANCEL'				=> 'Texto da página de colaboração cancelada',
	'DONATION_CANCEL_EXPLAIN'			=> 'Entre com um texto a ser exibido caso a colaboração seja cancelada.<br />Esta página é exibida após o cancelamento da colaboração.<br /><br />HTML está disponível.',

	'DONATION_DISABLED'				=> 'Desculpe-nos, mas a página de colaboração está indisponível.',
	'DONATION_DISABLED_EMAIL'			=> 'Conta de e-mail do Paypal ou PagSeguro não foram configuradas. Por favor, notifique o administrador.',
	'DONATION_NOT_INSTALLED'			=> 'Os registros em banco de dados da extensão Donation não foram encontrados.<br />Por favor, execute o %sinstaller%s para realizar a modificação no banco de dados.',
	'DONATION_NOT_INSTALLED_USER'			=> 'A página de colaboração não está instalada. Por favor, notifique o administrador.',
	'DONATION_TITLE'				=> 'Faça uma colaboração',
	'DONATION_DESCRIPTION'				=> 'Colaboração ao',	
	'DONATION_TITLE_HEAD'				=> 'Faça uma colaboração para',
	'WE_HAVE_ACHIEVED'				=> 'Nós recebemos',
	'WE_HAVE_ACHIEVED_IN'				=> 'em colaborações.',
	'OUR_DONATION_GOAL'				=> 'Nossa meta é atingir',
	'DONATION_CANCELLED_TITLE'			=> 'Colaboração cancelada',
	'DONATION_SUCCESSFULL_TITLE'			=> 'Colaboração bem sucedida',
	'DONATION_CONTACT_SERVICE'			=> 'Conectando... Por favor, aguarde…',
	'DONATION_BODY_DEFAULT'				=> 'Por favor, ajude a manter o fórum funcionando. Colabore!',
	'DONATION_SUCCESS_DEFAULT'			=> 'Obrigado pela colaboração. Todos os participantes do fórum agradecem.',
	'DONATION_CANCEL_DEFAULT'			=> 'Você cancelou a sua colaboração. Não tem problema. Por favor, futuramente considere se tornar um colaborador.',
	'DONATION_ADMIN_DEFAULT'			=> 'Este texto pode ser alterado em ACP, sob a extensão Donation.',
	'DONATIONS_INDEX'				=> 'Colaborações',
	'DONATION_BRL'					=> '$ BRL',
	'DONATION_USD'					=> '$ USD',
	'DONATION_EUR'					=> '€ EUR',
	'DONATION_GBP'					=> '£ GBP',
	'DONATION_JPY'					=> '¥ JPY',
	'DONATION_AUD'					=> '$ AUD',
	'DONATION_CAD'					=> '$ CAD',
	'DONATION_HKD'					=> '$ HKD',
	'DONATION_FREQUENCY'				=> 'Frequência',
	'DONATION_FREQUENCY_EXPLAIN'			=> 'Frequência com que as colaborações deverão acontecer.',
	'DONATION_YEARLY'				=> 'Anual',
	'DONATION_SEMESTRIAL'				=> 'Semestral',
	'DONATION_QUARTERLY'				=> 'Trimestral',
	'DONATION_MONTHLY'				=> 'Mensal',
	'DONATION_GROUP'				=> 'Grupo',
	'DONATION_GROUP_EXPLAIN'			=> 'Grupo a ser atribuído aos membros colaboradores.',
	'DONATION_NO_GROUPS'				=> 'Nenhum grupo válido.',
	'DONATION_PAY_IMAGE_LABEL'			=> 'Clique na imagem abaixo para continuar',
	'DONATION_YEARLY_MSG'				=> 'válida por 1 ano',
	'DONATION_SEMESTRIAL_MSG'			=> 'válida por 6 meses',
	'DONATION_QUARTERLY_MSG'			=> 'válida por 3 meses',
	'DONATION_MONTHLY_MSG'				=> 'válida por 1 mês',
	'DONATION_CURRENCY_SYMBOL'			=> 'R$',
	'DONATION_DONATIONS'				=> 'Colaborações',
	
	'DONATION_ADD'					=> 'Nova colaboração',
	
	'DONATION_FIRST'				=> 'Colaborador desde',
	'DONATION_FIRST_EXPLAIN'                        => 'Data da primeira colaboração que o membro fez.',
	
	'DONATION_LAST'					=> 'Última colaboração',
	'DONATION_LAST_EXPLAIN'                         => 'Data da última colaboração ou da colaboração que se está cadastrando.',
	
	'DONATION_EXPIRATION'				=> 'Colaborador até',
	'DONATION_EXPIRATION_EXPLAIN'                   => 'Data em que a colaboração vence.<br/>Deixe em branco para calcular automaticamente.',
	
	'DONATION_METHOD'				=> 'Método',
	'DONATION_METHOD_EXPLAIN'			=> 'Método que foi usado para recebimento da colaboração.',
		
	'DONATION_ADDED_BY_HUMAN' 			=> 'Incluído manualmente?',
	'DONATION_ADDED_BY_HUMAN_EXPLAIN'               => 'A colaboração foi ou está sendo criada ou alterada pelo administrador do fórum?<br />Responda "Não" se foi registrada automaticamente.',
	
	'DONATION_GIFT_SENT'				=> 'Brinde enviado?',
	'DONATION_GIFT_SENT_EXPLAIN'                    => 'O brinde que acompanha a colaboração já foi enviado?',
	
	'DONATION_STATUS'				=> 'Situação da colaboração',
	'DONATION_STATUS_EXPLAIN'                       => 'Situação da colaboração. Inativa, quando vencida ou cancelada pelo administrador; ativa, quando está no prazo de válidade; e aguardando ativação, quando foi ainda é necessária alguma ação para que a colaboração seja efetivada.',
	
	'DONATION_MSG_STATUS'				=> 'Situação das mensagens',
	'DONATION_MSG_STATUS_EXPLAIN'			=> 'Situação de envio das MPs de alerta.<br />Vencimento em 30 dias; Vencimento em 15 dias; Vencimento em 1 dia; Vencida.',	
	
	'DONATION_COMMENTS'				=> 'Observações',
	'DONATION_COMMENTS_EXPLAIN'			=> 'Observações e anotação sobre a colaboração.<br />Não aparece para o colaborador.',

	'DONATION_EMPTY'				=> 'Nenhuma colaboração realizada ou nenhuma colaboração atendeu aos critérios do filtro.',
	'DONATION_NOT_LOGGED'				=> 'Você deve entrar com seu usuário registrado antes de continuar.',
	'DONATION_RENOVATION'				=> 'Pagando novamente, consideraremos uma renovação, cuja validade será',
	'DONATION_PAY_ALERT'				=> 'Ao continuar, não use os botões próximo ou voltar de seu navegador.<br />Use apenas os links e botões existentes nos corpos das próximas páginas.',
	'DONATION_REMAINING'				=> 'vence em',
	
	'DONATION_ACTIVE'				=> 'Ativa',
	'DONATION_INACTIVE'				=> 'Inativa',
	'DONATION_WAITING_ACTIVATION'			=> 'Aguardando ativação',
	
	'DONATION_MSG_0'				=> 'Nenhuma mensagem enviada',
	'DONATION_MSG_1'				=> 'Enviado alerta de vencimento em 30 dias',
	'DONATION_MSG_2'				=> 'Enviado alerta de vencimento em 15 dias',
	'DONATION_MSG_3'				=> 'Enviado alerta de vencimento hoje',
	'DONATION_MSG_4'				=> 'Enviado alerta de vencimento',
	
	'DONATION_DONOR'				=> 'Colaborador',
	'DONATION_DONOR_EXPLAIN'                        => 'Nome do usuário que fez a colaboração.',
	
	'DONATION_EXPIRED'				=> 'vencida',
	'DONATION_EDIT'					=> 'Editar colaboração',
	'U_R_DONOR'                                     => 'Você já é um colaborador!',
	'DONATION_SEND_NOTICE'				=> 'Os adesivos serão enviados para o endereço cadastrado no PayPal.',
	
	'DONATION_METHOD_PAYPAL'                        => 'PayPal',
	'DONATION_METHOD_CHECKING_ACCOUNT'              => 'Conta corrente / poupança',
	'DONATION_METHOD_IN_PERSON'                     => 'Em pessoa',
	'DONATION_METHOD_OTHER'                         => 'Outro',
	
	'ACP_DONATION_DELETE_CONFIRM'                   => 'Excluir a colaboração?<br /><b>ATENÇÃO!</b> Não será possível desfazer esta operação.',
	'ACP_DONATION_ACTIVATE_CONFIRM'                 => 'Ativar colaboração?',
	'ACP_DONATION_DEACTIVATE_CONFIRM'               => 'Desativar a colaboração?',
	'ACP_DONATION_SEND_GIFT_CONFIRM'                => 'Enviar brinde?',
	'ACP_DONATION_SYNC_CONFIRM'			=> 'Sincronizar agora?<br />Esta operação irá verificar quem as colaborações vencidas ou recebidas e incluir ou remover do grupo, conforme a necessidade.<br />Também, recontará os colaboradores ativos e atualizará o contador das estatísticas da página inicial.',
	'ACP_DONATION_MSG_CONFIRM'			=> 'Enviar mensagens agora?<br />Esta operação enviará MPs aos para quem está com sua colaboração para vencer em 30, 15 e 1 dia.<br />Também, alertará os que estão com a colaboração vencida.<br />Somente uma mensagem de alerta de cada tipo é enviada.',
	
	'DONATION_ACTIVATE'				=> 'Ativar colaboração',
	'DONATION_DEACTIVATE'				=> 'Inativar colaboração',
	'DONATION_SEND'					=> 'Enviar brinde',

	'ACP_DONATIONS_ADD_DONATION'			=> 'Nova colaboração',
	'ACP_DONATIONS_ADD_DONATION_EXPLAIN'		=> 'Informe manualmente uma colaboração',
	'ACP_DONATIONS_EDIT_DONATION'			=> 'Modificar a colaboração',
	'ACP_DONATIONS_EDIT_DONATION_EXPLAIN'		=> 'Altere os dados de uma colaboração. Informe o envio do brinde, por exemplo.',
	'ACP_DONATIONS_MANAGE_DONATIONS'		=> 'Gerenciar colaborações',
	'ACP_DONATIONS_MANAGE_DONATIONS_EXPLAIN'	=> 'Gerencie as colaborações recebidas.',
	'ACP_DONATIONS_SYNC_DONATIONS'			=> 'Sincronização de colaboradores',
	'ACP_DONATIONS_MSG_DONORS'			=> 'Envio de mensagens aos colaboradores',
	
	'ACP_DONATION_CREATED_CHANGED_LOG'		=> '<strong>Colaboração de usuário</strong> %1$s <strong>inserida ou alterada </strong>',
	'ACP_GIFT_SENT_DONATION_LOG'                    => '<strong>Brinde enviado para usuário</strong> %1$s',
	'ACP_DONATION_DEACTIVATE_LOG'                   => '<strong>Colaboração inativada de usuário</strong> %1$s',
	'ACP_DONATION_ACTIVATE_LOG'                     => '<strong>Colaboração ativada de usuário</strong> %1$s',
	'DONATION_SAVED_LOG'                            => '<strong>Configurações de Donation gravadas</strong>',
	'ACP_DELETE_DONATION_LOG'                       => '<strong>Colaboração excluída de usuário</strong> %1$s',
	'DONATION_MSG_1_LOG'                            => '<strong>Enviado alerta de vencimento em 30 dias para usuário</strong> %1$s',
	'DONATION_MSG_2_LOG'                            => '<strong>Enviado alerta de vencimento em 15 dias para usuário</strong> %1$s',
	'DONATION_MSG_3_LOG'                            => '<strong>Enviado alerta de vencimento hoje para usuário</strong> %1$s',
	'DONATION_MSG_4_LOG'                            => '<strong>Enviado alerta de vencimento para usuário</strong> %1$s',
	'DONATION_CRON_LOG'                             => 'Cron diária de sincronização e mensagens para colaboradores executou em %12$s.<br />» Colaboradores: %1$s »Inativos: %2$s » Adicionados ao grupo %3$s (%4$s) » Removidos do grupo: %5$s (%6$s)<br /> » Mensagens enviadas: %7$s » 30 dias: %8$s » 15 dias: %9$s » Hoje: %10$s » Vencimento: %11$s',
	
	'EXPIRATION_GT_LAST_EXCEPTION'                  => 'Data de vencimento deve ser maior ou igual a data da última colaboração',
	'EMPTY_DONOR_EXCEPTION'                         => 'Colaborador não pode ser vazio.',
	'DONOR_NOT_FOUND_EXCEPTION'                     => 'Colaborador não localizado!<br />Não existe usuário registrado correspondente ao colaborador.',
	'FIRST_GT_LAST_EXCEPTION'                       => 'Primeira colaboração é posterior à última colaboração.',
	
	'ACP_DONATION_EDIT_SUCCESS'                     => 'Colaboração gravada com sucesso.',
	'ACP_DONATION_DELETE_SUCCESS'                   => 'Colaboração removida com sucesso.',
	
	'DONORS'					=> 'Colaboradores', 

	'ACTIVE_DONORS'					=> 'Colaboradores ativos',
	'INACTIVE_DONORS'				=> 'Colaboradores inativos',
	'DONORS_ADDED'					=> 'Colaboradores adicionados ao grupo',
	'DONORS_REMOVED'				=> 'Colaboradores removidos do grupo',
	'MESSAGES_SENT'					=> 'Mensagens enviadas',
	
	'DONATION_SYNC'					=> 'Sincronizar',
	'DONATION_SEND_MESSAGE'				=> 'Enviar MPs',
	
	'LAST_DONOR'					=> 'Mais nova colaboração/renovação',
));
