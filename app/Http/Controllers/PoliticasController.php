<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PoliticasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacidade() {
        return Inertia::render('PoliticaPrivacidade');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cookies() {
        Inertia::share('pagina.titulo', 'Política de Cookies | Dell Anno');
        Inertia::share('pagina.tituloCompartilhamento', 'Política de Cookies | Dell Anno');

        $conteudo = '
        <p class="font-16">O que são cookies?</p>
                <p>
                Um cookie é uma pequena sequência de texto que um site envia ao
                navegador e salva em seu computador quando você visita sites da
                Internet. Os cookies são utilizados para permitir que o site
                opere de forma mais eficiente e melhore o seu desempenho, mas
                também para fornecer informações ao proprietário do site.
            </p>
            <p>
                Os cookies são utilizados para diversos fins, têm diferentes
                características e podem ser utilizados pelo proprietário do site
                que você está visitando ou por terceiros. Abaixo você encontrará
                todas as informações sobre os cookies instalados através deste
                site, bem como as instruções necessárias sobre como gerenciar
                suas preferências em relação aos mesmos.
            </p>
            <p>
                Para obter mais informações sobre cookies e suas funções gerais,
                visite um site informativo, por exemplo
                <a href="https://pt.wikipedia.org/wiki/Cookie_(inform%C3%A1tica)">
                    https://pt.wikipedia.org/wiki/Cookie_(informática)
                </a>
                .
            </p>
            <p><strong>Cookies usados por este site</strong></p>
            <p>
                A utilização de cookies pelo proprietário deste site, Unicasa
                Indústria de Móveis - Endereço: BR 470 Km 212, 930 São
                Vendelino, Bento Gonçalves - RS. 905707-540 - Brasil, faz parte
                da Política de Privacidade do referido - para todas as
                informações relativas à nossa Política de Privacidade,
                <a href="' . route('Politicas.privacidade') . '">Clique AQUI.</a>
            </p>
            <p>
                <strong>
                    Cookies técnicos que não requerem consentimento:
                </strong>
            </p>
            <div>
                <table border="1" cellspAcing="0" cellpadding="0" width="100%">
                    <thead>
                        <tr>
                            <td colspan="7">Cookies relacionados com atividades estritamente necessárias para o
                                funcionamento do
                            site e a prestação de serviços</td>
                        </tr>
                        <tr>
                            <th>Cookie</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Expira em</th>
                            <th>Domínio</th>
                            <th>Usado em</th>
                            <th>Quem tem acesso?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>policies-set</td>
                            <td>Cookie de Personalização</td>
                            <td>essencial</td>
                            <td>1 ano</td>
                            <td>dellanno.com.br</td>
                            <td>Em todo o site</td>
                            <td>Dell Anno</td>
                        </tr>
                        <tr>
                            <td>cookies-allowed</td>
                            <td>Cookie de Personalização</td>
                            <td>essencial</td>
                            <td>1 ano</td>
                            <td>dellanno.com.br</td>
                            <td>Em todo o site</td>
                            <td>Dell Anno</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <br>
            <div>
                <table border="1" cellspAcing="0" cellpadding="0" width="100%">
                    <thead>
                        <tr>
                            <td colspan="7">Cookies relacionados com as atividades de salvar preferências e
                            otimização</td>
                        </tr>
                        <tr>
                            <th>Cookie</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Expira em</th>
                            <th>Domínio</th>
                            <th>Usado em</th>
                            <th>Quem tem acesso?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>user-preferences</td>
                            <td>Cookie de Personalização</td>
                            <td>essencial</td>
                            <td>1 ano</td>
                            <td>dellanno.com.br</td>
                            <td>Em todo o site</td>
                            <td>Dell Anno</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <p>
                O site também usa cookies estatísticos de terceiros (Google
                Analytics) para coletar informações de forma agregada, definida
                como um cookie técnico, ou seja, sem rastrear o IP do usuário
                (dados do usuário não apresentados no IP) e sem compartilhar os
                dados com Terceiros.
            </p>

            <p>
                <strong>Acesso a informações de terceiros:</strong>
                <br />
                Política de privacidade:
                <a href="http://www.google.com/policies/privacy/">
                    <strong>Política de Privacidade Privacidade & Termos Google</strong>
                </a>
                <br />
                Política de cookies:
                <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/cookie-usage">
                    <strong>Uso de cookies do Google Analytics em sites</strong>
                </a>
                <br />
                Para desativar:
                <a href="https://tools.google.com/dlpage/gaoptout?hl=it">
                    <strong>Página de download do Add-on do navegador para desativação
                    do Google Analytics</strong>
                </a>
                <br />
            </p>

            <p>
                Todos os cookies técnicos não requerem consentimento; portanto,
                eles foram instalados automaticamente devido ao acesso ao site.
            </p>

            <p><strong>Cookies de Redirecionamento</strong></p>

            <p>
                São utilizados para o envio de publicidade a sujeitos que já
                visitaram este site. Você encontrará abaixo o nome de terceiros
                que gerenciam os cookies e para cada um deles, o link da página
                onde pode receber informações sobre o seu processamento e dar o
                seu consentimento.
            </p>

            <p><strong>Cookies técnicos que não requerem consentimento</strong></p>
            <div>
                <div>
                    <table border="1" cellspAcing="0" cellpadding="0" width="100%">
                        <thead>
                            <tr>
                                <td colspan="7">Cookies relacionados com atividades estritamente necessárias para o
                                funcionamento do site e a prestação de serviços</td>
                            </tr>
                            <tr>
                                <th>Cookie</th>
                                <th>Descrição</th>
                                <th>Categoria</th>
                                <th>Expira em</th>
                                <th>Domínio</th>
                                <th>Usado em</th>
                                <th>Quem tem acesso?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Facebook Pixel</td>
                                <td>Retargeting e LookLike</td>
                                <td>CRM</td>
                                <td>1 ano</td>
                                <td>facebook.com</td>
                                <td>Em todo o site</td>
                                <td>Dell Anno</td>
                            </tr>
                            <tr>
                                <td>Instagram Pixel</td>
                                <td>Retargeting e LookLike</td>
                                <td>CRM</td>
                                <td>1 ano</td>
                                <td>instagram.com</td>
                                <td>Em todo o site</td>
                                <td>Dell Anno</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="et_pb_contact">
<div class="form__row">
    <div class="form__group">
    <span style="display: block; margin-top: 10px;" class="et_pb_contact_field_options_title"><strong>Privacidade e Política</strong></span>
    </div>
</div>

<div class="form__row" style="margin-top: 10px;">
    <div class="form__group">
    <span class="et_pb_contact_field_options_list"><span class="et_pb_contact_field_checkbox">
        <input type="checkbox" id="et_pb_contact_field_4_0_0" class="input" value="I consent" data-id="-1">
        <label for="et_pb_contact_field_4_0_0"><i></i>Eu aceito</label>
    </span></span>
    </div>
</div>

<div class="form__row" style="margin-top: 0;">
    <div class="form__group">
    <span class="et_pb_contact_field_options_list"><span class="et_pb_contact_field_checkbox">
        <input type="checkbox" id="et_pb_contact_dsad_1_0" class="input" value="I do not consent" data-id="-1">
        <label for="et_pb_contact_dsad_1_0"><i></i>Eu não aceito</label>
    </span></span>
    </div>
</div>

<div class="form__row" style="margin-top: 10px;">
    <div class="form__group">
    <button type="submit" name="et_builder_submit_button" class="et_builder_submit_button form__submit btn mx-auto">Salvar</button>
    </div>
</div>
</div>
<div class="et_pb_contact--feedback" style="margin: 20px 0; display: none;">
<span class="et_pb_contact_field_options_title">Obrigado pelo feedback!</span>
</div>

            <p><strong>Interação com redes sociais e plataformas externas</strong></p>

            <p>
                Lembre-se de que você pode gerenciar suas preferências de
                cookies também por meio do navegador. Se você não sabe o tipo e
                a versão do navegador que está usando, clique em "Ajuda" na
                janela do navegador na parte superior, lá você pode acessar
                todas as informações necessárias. Se você conhece seu navegador,
                clique naquele que está usando para acessar a página de
                gerenciamento de cookies.
            </p>

            <p>
                Internet Explorer
                <a href="http://windows.microsoft.com/en-us/windows-vista/block-or-allow-cookies">
                    <strong>Eliminar e gerir cookies (microsoft.com)</strong>
                </a>
                <br />
                Google Chrome
                <a href="https://support.google.com/chrome/answer/95647?hl=pt-pt">
                    <strong>https://support.google.com/chrome/answer/95647?hl=pt-pt</strong>
                </a>
                <br />
                Mozilla Firefox
                <a href="http://windows.microsoft.com/en-us/windows-vista/block-or-allow-cookies">
                    <strong>Desative cookies de terceiros no Firefox para impedir alguns
                    tipos de rastreamento por anunciantes | Ajuda do Firefox
                    (mozilla.org)</strong>
                </a>
                <br />
                Safari
                <a href="http://windows.microsoft.com/en-us/windows-vista/block-or-allow-cookies">
                    <strong>Limpar o histórico e os cookies do Safari no iPhone, iPad ou
                    iPod touch - Suporte da Apple (BR)</strong>
                </a>
            </p>
        ';

        return Inertia::render('PoliticaCookies', [
            'conteudo' => $conteudo
        ]);
    }
};