<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ManualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $texto = '    <ol start="1" type="1">
                <li><strong>USO DOS MÓVEIS</strong></li>
            </ol>
            <br>
            <ul type="circle">
                <li>Após a instalação dos móveis, deixe-os sem qualquer tipo de peso por aproximadamente 24 horas. Este é o tempo necessário para a colagem completa dos produtos;</li>
                <li>A áreas ao redor da pia, do forno e dos rodapés são mais suscetíveis à ação da umidade e merecem atenção especial;</li>
                <li>Seque de imediato qualquer líquido derramado sobre os móveis;</li>
                <li>Abra totalmente e com cuidado as portas do seu móvel. Durante o manuseio, evite batê-las ou deixá-las entreabertas, de modo que não ocorram colisões com gavetas, pois isso pode danificar o revestimento das peças;</li>
                <li>Os raios solares não devem incidir diretamente sobre os móveis, pois podem alterar suas características, tornando-os amarelados e prejudicando sua durabilidade. Sugerimos a utilização de persianas, cortinas ou películas;</li>
                <li>Ao mover objetos acima dos móveis para a limpeza, levante-os e não arraste, pois isso pode causar riscos no revestimento dos móveis;</li>
                <li>Não coloque peso excessivo sobre os móveis e nunca se apoie sobre as portas, pois isso pode ocasionar empenamento, desregulamento ou quebra;</li>
                <li>Não se apoie sobre as gavetas para alcançar as partes superiores. Deve-se ter atenção com as crianças, pois elas geralmente utilizam as gavetas abertas como “escada” para subir nos balcões;</li>
                <li>Não estenda toalhas úmidas ou molhadas sobre as portas dos móveis. Ao longo do tempo, a umidade pode causar danos permanentes;</li>
                <li>Não apoie panelas, fôrmas, assadeiras e demais utensílios aquecidos sobre os balcões, pois isso pode causar danos. Sempre utilize apoios ou amparos para colocar objetos quentes sobre os balcões;</li>
                <li>Não corte alimentos diretamente sobre os balcões, pois os instrumentos de corte poderão danificar o acabamento dos produtos. Sempre utilize apoios para proteção;</li>
                <li>Não utilize estiletes e outros objetos com lâminas ou pontiagudos para fazer cortes sobre tampos. Os revestimentos podem ser riscados ou danificados permanentemente;</li>
                <li>Evite o contato de tinta (canetas em geral), nos revestimentos dos armários e dos tampos, pois podem causar manchas. Utilize porta-canetas e anteparos para guardar esses materiais;</li>
                <li>Sempre mantenha os móveis livres da umidade. Utilize vedantes de silicone para vedar a junção dos tampos e das pias com o revestimento das paredes. Verifique as instalações periodicamente a fim de evitar vazamento.</li>
            </ul>
            <br>

            <ol start="2" type="1">
                <li><strong>PESO</strong></li>
            </ol>
            <br>

            <ul type="circle">
                <li>Confira o peso máximo suportado (sem danos aos móveis) para cada prateleira interna de superiores e inferiores.</li>
            </ul>

            <p>
                <strong>Cozinha e Banheiro</strong><br>
            </p>
            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>Largura</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>Prateleira Superior</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>Prateleira Inferior</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">40cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">6kg</p>
                        </td>
                        <td valign="top">
                            <p align="center">6kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">60cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">9kg</p>
                        </td>
                        <td valign="top">
                            <p align="center">9kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">80cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">13kg</p>
                        </td>
                        <td valign="top">
                            <p align="center">13kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">100cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">16kg</p>
                        </td>
                        <td valign="top">
                            <p align="center">16kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">120cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">18kg</p>
                        </td>
                        <td valign="top">
                            <p align="center">18kg</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                <strong>Dormitórios, Home Office Home Theather</strong><br>
            </p>
            <table border="1" cellspacing="0" cellpadding="0" width="80%">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>Largura</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>Prateleira</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">35cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">6kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">45cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">8kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">70cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">12kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">90cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">15kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">100cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">16kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">110cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">16kg</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">120cm</p>
                        </td>
                        <td valign="top">
                            <p align="center">16kg</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                <strong>Prateleiras Externas (Decorativas)</strong><br>
                <br>
                As prateleiras externas suportam 10kg por metro linear, respeitando as seguintes condições:
            </p>
            <ul type="circle">
                <li>As prateleiras externas suportam 10kg por metro linear, respeitando as seguintes condições:<br>
                    - Suporte de sustentação a cada 60cm;<br>
                    - Fixação nas extremidades.<br></li>
            </ul>
            <p>
                Fique atento:<br>
                Para que não ocorram danos aos móveis, o peso máximo correspondente a cada medida foi calculado com base na exata distribuição do peso na superfície da prateleira.
            </p>
            <br>
            <ol start="3" type="1">
                <li><strong>LIMPEZA E CONSERVAÇÃO</strong></li>
            </ol>
            <br>
            <p>
                3.1 <strong>Módulos/Painéis/Portas</strong>
            </p>
            <ul type="circle">
                <li>Sempre utilize um pano limpo e macio ligeiramente umedecido com água morna (apenas o bastante para fazer aderir a poeira), secando logo em seguida. Para manchas mais resistentes, use um pano limpo, umedecido com sabão neutro (não alcalino) e água. Após a limpeza, seque completamente com um pano macio.</li>
            </ul>
            <p>
                Lembre-se:<br>
                O acúmulo de poeira, gordura ou umidade, com o tempo, pode modificar a característica do produto. Por isso, não se esqueça de limpar os móveis regularmente!
            </p>

            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>NÃO UTILIZAR</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>PORQUE:</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Pano encharcado.</p>
                        </td>
                        <td valign="top">
                            <p align="center">O acúmulo de água pode causar danos ao móvel.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Pano de louça.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Pode conter resíduos.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Pano seco.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Além de não limpar, com o tempo, desgasta o móvel.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Ceras.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Pode deixar resíduos, causando manchas no móvel.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Detergentes*, limpadores instantâneos, desengordurantes ou produtos à base de amônia e saponáceo.</p>
                        </td>
                        <td valign="top">
                            <p align="center">São produtos químicos que podem danificar a aparência e o acabamento dos móveis.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Esponjas duras, ásperas e palhas de aço.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Podem riscar e danificar os móveis.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                *É recomendável apenas o uso de detergentes neutros.
            </p>
            <br>
            <p class="background-black color-white" style="padding: 20px;">
                <strong>Evite:</strong><br>
                Cuidado com materiais pontiagudos, eles podem riscar o móvel. Produtos como vinagre, sal e limão podem causar danos nos móveis com o passar do tempo.<br>
                Tome cuidado!
            </p>
            <br>
            <p>
                <strong>Peças Pintadas</strong><br>

                Pintura Alto Brilho (Color Shine)<br>
                Para a limpeza e conservação, utilize uma flanela úmida. Para secar, utilize uma flanela seca, mas lembre-se de evitar o atrito excessivo.<br>
                <br>

                Também é aconselhável o uso de uma pequena camada de cera à base de silicone (ex.: cera de uso automotivo). Essa cera, além de limpar, fornece uma aparência renovada e uma camada protetora. Passe uma pequena quantidade com a ajuda de uma flanela, e logo após lustre com um pano de lã para dar o devido brilho.<br>
                Este processo deve ser feito somente nas portas que possuem Pintura Alto Brilho (Color Shine), devendo ser repetido a cada 90 dias.<br>
                <br>

                Pintura Acetinada/Fosca (Satin Color)<br>
                Utilize apenas água e detergente neutro diretamente em uma flanela apropriada. Após a limpeza, seque completamente com uma flanela seca, sempre evitando o atrito excessivo.<br>
                <br>
                Fique atento:<br>
                A exposição excessiva ao sol pode ocasionar mudanças na tonalidade do móvel, além de ressecar a pintura. Evite!
            </p>

            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>NÃO UTILIZAR</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>PORQUE:</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Produtos de limpeza abrasivos como esponja de aço, saponáceos, álcool e outros.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Podem danificar o acabamento e riscar.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                <strong>Vidros e Espelhos</strong>
            </p>
            <ul type="circle">
                <li>Para a limpeza, utilize água e detergente neutro diretamente em um pano apropriado ou toalha de papel.</li>
            </ul>

            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>NÃO UTILIZAR</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>PORQUE:</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Pano seco, esponja de aço ou outro objeto abrasivo.</p>
                        </td>
                        <td valign="top">
                            <p align="center">O atrito pode danificar o vidro.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Qualquer produto pulverizado diretamente no vidro.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Pode causar danos nos revestimentos e acessórios caso entrem em contato com os móveis.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                <strong>Puxadores</strong><br>
                <br>
                Utilize apenas um pano macio, umedecido com água. Em seguida, seque totalmente.
            </p>

            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>NÃO UTILIZAR</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>PORQUE:</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Produtos de limpeza.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Podem manchar e causar danos aos puxadores.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Produtos ou objetos abrasivos</p>
                        </td>
                        <td valign="top">
                            <p align="center">Podem riscar e causar danos ao material dos puxadores</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Álcool, detergente ou solvente na limpeza dos puxadores em metal.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Removem a camada de verniz (quando aplicado sobre o puxador).</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                <strong>Dobradiças e Corrediças</strong><br>
            </p>
            <ul type="circle">
                <li>Utilize um pano macio ou pincel de cerdas suaves para retirar a poeira.<br>
                    <br>
                    Fique atento:<br>
                    Em regiões que possuem maresia, este processo deve ser feito a cada 30 dias.
                </li>
                <br>
                <li>Produtos como sal, limão e vinagre podem acelerar o processo de oxidação dos acessórios metálicos.</li>
            </ul>

            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>NÃO UTILIZAR</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>PORQUE:</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Produtos de limpeza.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Essas ferragens possuem camadas de proteção não perceptíveis ao contato direto e não devem receber interferência de quaisquer elementos/agentes físicos ou químicos.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Produtos ou objetos abrasivos</p>
                        </td>
                        <td valign="top">
                            <p align="center">Podem riscar e causar danos ao material das dobradiças e das corrediças.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p class="background-black color-white" style="padding: 20px;">
                <strong>Evite:</strong><br>
                O acúmulo de poeira, gordura ou umidade sobre as dobradiças e corrediças podem prejudicar o seu bom funcionamento e/ou ocasionar oxidação (ferrugem).
            </p>
            <p>
                <strong>Produtos Aramados</strong><br>
            </p>
            <ul type="circle">
                <li>A limpeza deve ser realizada a cada 60 dias. Utilize um pano umedecido com detergente neutro e água. Após a limpeza, seque completamente com um pano seco.</li>
                <li>Para ressaltar o brilho e durabilidade do produto, utilize a cera à base de silicone.</li>
                <li>No caso de aramados cromados, este processo de limpeza deve ser realizado a cada 30 dias.</li>
            </ul>

            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>NÃO UTILIZAR</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>PORQUE:</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Solventes (álcool e tiner), lixas, materiais ásperos ou cortantes (esponjas).</p>
                        </td>
                        <td valign="top">
                            <p align="center">Podem riscar ou danificar seu produto e provocar oxidação.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                Fique atento:<br>
                Em regiões que possuem maresia, este processo deve ser feito a cada 30 dias.<br>
                Produtos como sal, limão e vinagre podem acelerar o processo de oxidação dos acessórios cromados.
            </p>

            <p>
                <strong>Acessórios em Geral</strong><br>
            </p>
            <p>
                Peças de Inox e Esmaltados<br>
                As peças de aço inox e esmaltados deverão ser limpos com pano levemente umedecido e sabão neutro ou com desengordurantes (desde que tenham cloro e seus derivados).<br>
            </p>

            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>NÃO UTILIZAR</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>PORQUE:</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Acessórios com acabamento cromado em ambientes úmidos e regiões litorâneas.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Estão sujeitos à ocorrência de oxidação pela ação da água e da maresia.</p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Produtos ou objetos abrasivos</p>
                        </td>
                        <td valign="top">
                            <p align="center">Podem riscar e causar danos ao material das dobradiças e das corrediças.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                Observação:<br>
                Produtos especiais desenvolvidos para a limpeza e a conservação desses materiais em inox e esmaltados podem ser utilizados.
            </p>
            <p>
                <strong>Lâmina natural de madeira</strong><br>
            </p>
            <p>
                Para limpeza utilizar um pano limpo, macio, seco e sem fiapos. Em caso de limpeza pesada umedecer o pano com água e sabão neutro, secar a superfície logo após a limpeza.
            </p>

            <table border="1" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;">
                <tbody>
                    <tr>
                        <td>
                            <p align="center"><strong>NÃO UTILIZAR</strong></p>
                        </td>
                        <td>
                            <p align="center"><strong>PORQUE:</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <p align="center">Produtos abrasivos, produtos de limpeza ou lustra móveis.</p>
                        </td>
                        <td valign="top">
                            <p align="center">Pode alterar coloração, característica e acabamento do revestimento em lâmina natural de madeira</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p>
                <strong>Itens Revestidos</strong><br>
            </p>
            <p>
                Itens revestidos podem sofrer alteração de cor quando expostos diretamente a luz solar e/ou artificial, principalmente os revestimentos de cores mais escuras.
            </p>
            <p class="background-black color-white" style="padding: 20px;">
                <strong>Evite:</strong><br>
                O contato com roupas escuras, como jeans, pode manchar permanente o revestimento.
            </p>
            <p>
                Pele Sintética<br>
                Para limpeza utilizar pano limpo, macio e seco. Em caso de sujeiras ocasionais retirar imediatamente o excesso com papel absorvente ou pano limpo e, em seguida, sem esfregar, passar um pano umedecido em água e sabão neutro. Deixar secar naturalmente a sombra.
            </p>
            <p>
                Fique atento:<br>
                Não utilizar equipamentos a vapor, nem produtos com álcool, alvejante, cloro ou abrasivos. Não realizar lavagem industrial ou impermeabilização.
            </p>
            <p>
                Tecido Trama<br>
                Utilizar escova ou pincel de cerdas macias. Em caso de sujeiras ocasionais retirar imediatamente o excesso com papel absorvente ou pano limpo e, em seguida, sem esfregar, passar um pano umedecido em água e sabão neutro. Não concentrar a limpeza apenas na área da mancha para não danificar as fibras, não retirar o pigmento dos tecidos com cor e não marcar. Deixar secar naturalmente a sombra.
            </p>
            <p>
                Fique atento:<br>
                Não utilizar equipamentos a vapor, nem produtos com álcool, alvejante, cloro ou abrasivos. Não realizar lavagem industrial ou impermeabilização.
            </p>
            <br>

            <ol start="4" type="1">
                <li><strong>MATÉRIA-PRIMA</strong></li>
            </ol>
            <br>
            <ul type="circle">
                <li>Os móveis fabricados em MDF e MDP são expedidos pela fábrica sem qualquer espécie de fungos, cupins ou pragas em geral. As chapas de madeira são submetidas a equipamentos que utilizam altas temperaturas (aproximadamente 200ºc) e pressão em seu processo de fabricação, o que garante a inexistência de qualquer espécie de praga no produto.</li>
                <li>No entanto, por serem derivados da madeira, podem ser atacados, caso tenham contato com produtos, materiais ou locais já infestados.</li>
                <li>Em caso de histórico de cupins, ou qualquer outra praga, recomendamos que seja feita a identificação do foco e posterior dedetização/descupinização nos ambientes antes da instalação dos móveis (respeitando o período de isolamento do ambiente para início da montagem). Também recomendamos a frequência semestral de dedetização.</li>
                <li>Em relação à proliferação de fungos, a condição do ambiente é fator determinante. Devido ao fato de os fungos causadores do mofo/bolor estarem presentes no ar atmosférico, sendo esta sua principal via de dispersão, eles se instalam em ambientes que reúnam condições favoráveis para sua proliferação. Quando houver uma ocorrência de mofo/bolor em móveis é necessário, primeiramente remover as contaminações, a fim de evitar que os fungos se proliferem por outros móveis ou recintos da casa, efetuar limpeza periódica e controlar as condições do ambiente para evitar nova infestação.</li>
            </ul>
            <p>
                Lembre-se:<br>
                Substituir as peças infestadas por cupim é uma ação que não elimina as causas.<br>
                Por se tratar de matéria-prima que absorve umidade, não deve ser exposta à ação excessiva da água.
            </p>
            <br>

            <ol start="5" type="1">
                <li><strong>SUSTENTABILIDADE</strong></li>
            </ol>
            <br>
            <ul type="circle">
                <li>Na indústria, a preservação do meio ambiente se reflete na adoção de práticas sustentáveis em todas as etapas do processo de produção.</li>
                <li>A matéria-prima é proveniente de plantações florestais certificadas, próprias ou de terceiros, ou adquiridas de fontes controladas.</li>
                <li>As embalagens dos móveis da Unicasa são 95% feitas com material reciclável.</li>
                <li>O índice de reaproveitamento da água chega a 100% através do tratamento de efluentes e da captação de água da chuva, que juntas são utilizados como reserva de incêndio e nos sanitários de toda a empresa.</li>
                <li>O resíduo gerado no tratamento de efluentes é destinado a uma empresa da cidade vizinha de Veranópolis, produtora de fertilizantes orgânicos.</li>
                <li>Os co-produtos gerados no processo produtivo — como a serragem, madeira, plástico e papelão — são reaproveitados por outros setores da economia. Demais resíduos não recicláveis são encaminhados para processo de blendagem e coprocessamento, processo de alta tecnologia que não agride o meio ambiente e não deixa passivos ambientais para a empresa.</li>
                <li>Na área de energia, o óleo diesel foi substituído pelo GLP e a empresa possui telhas translúcidas que permitem a entrada de luz natural na planta fabril, diminuindo o consumo com iluminação.</li>
                <li>Em 2020, a Unicasa utilizou a quantidade de 255.820 kg de embalagens plásticas circulares, produzidas a partir de matéria-prima reciclada, práticas sustentáveis e energia eólica, resultando em diversos benefícios ambientais e sociais. Dados do Certificado de Circularidade:
                </li>
            </ul>
            <br>
            <div class="double--content">
                <div class="content--50">
                    <div class="columns" style="margin-bottom: 40px; flex-wrap: nowrap;">
                        <img src="/files/manual-icon-1.png" class="lazy" style="margin: 0 20px;" alt=""> <span>
                            Redução de <strong>511.640 kgCO2eq</strong><br>
                            de <strong>Gases do efeito estufa.</strong>
                        </span>
                    </div>

                    <div class="columns" style="margin-bottom: 40px; flex-wrap: nowrap;">
                        <img src="/files/manual-icon-2.png" class="lazy" style="margin: 0 20px;" alt=""> <span>
                            Redução de <strong>304.426 L</strong> de<br>
                            <strong>Consumo de Petróleo.</strong>
                        </span>
                    </div>

                    <div class="columns" style="margin-bottom: 40px; flex-wrap: nowrap;">
                        <img src="/files/manual-icon-3.png" class="lazy" style="margin: 0 20px;" alt=""> <span>
                            Redução de <strong>773.161 kW</strong> de<br>
                            <strong>Consumo de energia elétrica.</strong>
                        </span>
                    </div>
                </div>

                <div class="content--50">
                    <div class="columns" style="margin-bottom: 40px; flex-wrap: nowrap;">
                        <img src="/files/manual-icon-4.png" class="lazy" style="margin: 0 20px;" alt=""> <span>
                            Redução de <strong>de 255.820 kg</strong><br>
                            de <strong>Resíduos na natureza.</strong>
                        </span>
                    </div>

                    <div class="columns" style="margin-bottom: 40px; flex-wrap: nowrap;">
                        <img src="/files/manual-icon-5.png" class="lazy" style="margin: 0 20px;" alt=""> <span>
                            Redução de <strong>2.019.011 L</strong><br>
                            de <strong>Consumo de água no<br>
                                total dos processos.</strong>
                        </span>
                    </div>

                    <div class="columns" style="margin-bottom: 40px; flex-wrap: nowrap;">
                        <img src="/files/manual-icon-6.png" class="lazy" style="margin: 0 20px;" alt=""> <span>
                            <strong>2185 empregos</strong> diretos<br>
                            e indiretos.
                        </span>
                    </div>
                </div>
            </div>

            <ol start="6" type="1">
                <li><strong>OBSERVAÇÕES GERAIS</strong></li>
            </ol>
            <br>
            <ul type="circle">
                <li>No uso diário dos móveis, algumas situações devem ser evitadas a fim de prolongar a vida útil do produto.</li>
                <li>Atenção ao escolher os produtos de limpeza. Sempre verifique a sua composição química a fim de identificar a compatibilidade com os materiais dos móveis e dos acessórios.</li>
                <li>Em caso de dúvidas não previstas neste manual, entre em contato com a revenda onde os produtos foram adquiridos ou com nossa Central de Relacionamento com o Consumidor (CRC) disponível de segunda à sexta-feira, exceto feriados, das 9h às 17h, pelo telefone 0800 721 4104 ou acesse a seção no site <strong><a href="https://dellanno.com.br">www.dellanno.com.br</a></strong>.</li>
            </ul>';

        return Inertia::render('Manual', [
            'texto' => $texto,
            'arquivo' => rafator('files/dell-anno-hd_certificado_de_garantia.pdf')
        ]);
    }
};