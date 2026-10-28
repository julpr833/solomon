<?php

namespace Database\Seeders;

use App\Models\Proverbio;
use Illuminate\Database\Seeder;

class ProverbioSeeder extends Seeder
{
    public function run(): void
    {
        $proverbios = [
            ['capitulo' => 15, 'versiculo' => 1, 'texto' => 'La blanda respuesta quita la ira; mas la palabra áspera hace subir el furor.'],
            ['capitulo' => 13, 'versiculo' => 3, 'texto' => 'El que guarda su boca guarda su alma; mas el que mucho abre sus labios tendrá calamidad.'],
            ['capitulo' => 16, 'versiculo' => 3, 'texto' => 'Encomienda a Jehová tus obras, y tus pensamientos serán afirmados.'],
            ['capitulo' => 15, 'versiculo' => 13, 'texto' => 'El corazón alegre hermosea el rostro; mas por el dolor del corazón el espíritu se abate.'],
            ['capitulo' => 15, 'versiculo' => 16, 'texto' => 'Mejor es lo poco con el temor de Jehová, que el gran tesoro donde hay turbación.'],
            ['capitulo' => 10, 'versiculo' => 9, 'texto' => 'El que camina en integridad anda seguro; mas el que pervierte sus caminos será quebrantado.'],
            ['capitulo' => 16, 'versiculo' => 9, 'texto' => 'El corazón del hombre traza su rumbo, pero sus pasos los dirige el Señor.'],
            ['capitulo' => 25, 'versiculo' => 25, 'texto' => 'Como agua fría al alma sedienta, así son las buenas nuevas de lejanas tierras.'],
            ['capitulo' => 1,  'versiculo' => 7,  'texto' => 'El principio de la sabiduría es el temor de Jehová; los insensatos desprecian la sabiduría y la enseñanza.'],
            ['capitulo' => 3,  'versiculo' => 5,  'texto' => 'Fíate de Jehová de todo tu corazón, y no estribes en tu prudencia.'],
            ['capitulo' => 3,  'versiculo' => 6,  'texto' => 'Reconócelo en todos tus caminos, y él enderezará tus veredas.'],
            ['capitulo' => 4,  'versiculo' => 23, 'texto' => 'Sobre toda cosa guardada, guarda tu corazón; porque de él mana la vida.'],
            ['capitulo' => 10, 'versiculo' => 12, 'texto' => 'El odio despierta las rencillas; mas la caridad cubrirá todas las faltas.'],
            ['capitulo' => 11, 'versiculo' => 2,  'texto' => 'Cuando viene la soberbia, viene también la deshonra; mas con los humildes está la sabiduría.'],
            ['capitulo' => 11, 'versiculo' => 25, 'texto' => 'El alma generosa será prosperada; y el que saciare, él también será saciado.'],
            ['capitulo' => 12, 'versiculo' => 25, 'texto' => 'La congoja en el corazón del hombre lo abate; mas la buena palabra lo alegra.'],
            ['capitulo' => 13, 'versiculo' => 20, 'texto' => 'El que anda con los sabios, sabio será; mas el que se junta con necios será quebrantado.'],
            ['capitulo' => 14, 'versiculo' => 29, 'texto' => 'El que tarde se aira, es grande de entendimiento; mas el impaciente de espíritu enaltece la necedad.'],
            ['capitulo' => 15, 'versiculo' => 22, 'texto' => 'Los pensamientos son frustrados donde no hay consejo; mas en la multitud de consejeros se afirman.'],
            ['capitulo' => 16, 'versiculo' => 18, 'texto' => 'Antes del quebrantamiento es la soberbia; y antes de la caída la altivez de espíritu.'],
            ['capitulo' => 16, 'versiculo' => 24, 'texto' => 'Panal de miel son los dichos suaves; suavidad al alma y medicina a los huesos.'],
            ['capitulo' => 16, 'versiculo' => 32, 'texto' => 'Mejor es el que tarda en airarse que el fuerte; y el que se enseñorea de su espíritu, que el que toma una ciudad.'],
            ['capitulo' => 17, 'versiculo' => 17, 'texto' => 'En todo tiempo ama el amigo; y es como un hermano en tiempo de angustia.'],
            ['capitulo' => 17, 'versiculo' => 22, 'texto' => 'El corazón alegre constituye buen remedio; mas el espíritu triste seca los huesos.'],
            ['capitulo' => 18, 'versiculo' => 10, 'texto' => 'Torre fuerte es el nombre de Jehová; a él correrá el justo, y será levantado.'],
            ['capitulo' => 18, 'versiculo' => 21, 'texto' => 'La muerte y la vida están en poder de la lengua; y el que la ama comerá de sus frutos.'],
            ['capitulo' => 19, 'versiculo' => 21, 'texto' => 'Muchos pensamientos hay en el corazón del hombre; mas el consejo de Jehová permanecerá.'],
            ['capitulo' => 21, 'versiculo' => 23, 'texto' => 'El que guarda su boca y su lengua, guarda su alma de angustias.'],
            ['capitulo' => 22, 'versiculo' => 1,  'texto' => 'De más estima es el buen nombre que las muchas riquezas, y la buena fama más que la plata y el oro.'],
            ['capitulo' => 22, 'versiculo' => 6,  'texto' => 'Instruye al niño en su carrera; y aun cuando fuere viejo no se apartará de ella.'],
            ['capitulo' => 27, 'versiculo' => 17, 'texto' => 'Hierro con hierro se aguza; y así el hombre aguza el rostro de su amigo.'],
            ['capitulo' => 28, 'versiculo' => 13, 'texto' => 'El que encubre sus pecados no prosperará; mas el que los confiesa y se aparta alcanzará misericordia.'],
            ['capitulo' => 29, 'versiculo' => 25, 'texto' => 'El temor del hombre pondrá lazo; mas el que confía en Jehová será exaltado.'],
        ];

        foreach ($proverbios as $proverbio) {
            Proverbio::updateOrCreate(
                [
                    'Capitulo' => $proverbio['capitulo'],
                    'Versiculo' => $proverbio['versiculo'],
                ],
                ['Proverbio' => $proverbio['texto']],
            );
        }
    }
}
