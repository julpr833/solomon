<?php

namespace Database\Seeders;

use App\Models\FraseMotivacional;
use Illuminate\Database\Seeder;

class FraseMotivacionalSeeder extends Seeder
{
    public function run(): void
    {
        $frases = [
            ['texto' => 'La única forma de hacer un gran trabajo es amar lo que haces.', 'fuente' => 'Steve Jobs'],
            ['texto' => 'No juzgues cada día por la cosecha que recoges, sino por las semillas que plantas.', 'fuente' => 'Robert Louis Stevenson'],
            ['texto' => 'La motivación es lo que te pone en marcha. El hábito es lo que hace que sigas.', 'fuente' => 'Jim Ryun'],
            ['texto' => 'El éxito no es la clave de la felicidad. La felicidad es la clave del éxito.', 'fuente' => 'Albert Schweitzer'],
            ['texto' => 'Los pequeños hábitos diarios son los que construyen los grandes resultados.', 'fuente' => 'Anónimo'],
            ['texto' => 'Cree que puedes y casi habrás llegado.', 'fuente' => 'Theodore Roosevelt'],
            ['texto' => 'No cuentes los días, haz que los días cuenten.', 'fuente' => 'Muhammad Ali'],
            ['texto' => 'La disciplina es el puente entre las metas y los logros.', 'fuente' => 'Jim Rohn'],
            ['texto' => 'El único modo de hacer un gran trabajo es amar lo que haces.', 'fuente' => 'Aristóteles'],
            ['texto' => 'Lo que no te mata, te hace más fuerte.', 'fuente' => 'Friedrich Nietzsche'],
            ['texto' => 'La mente es todo. En lo que piensas te conviertes.', 'fuente' => 'Buda'],
            ['texto' => 'El futuro pertenece a quienes creen en la belleza de sus sueños.', 'fuente' => 'Eleanor Roosevelt'],
            ['texto' => 'No importa cuán lento vayas mientras no te detengas.', 'fuente' => 'Confucio'],
            ['texto' => 'El secreto para avanzar es empezar.', 'fuente' => 'Mark Twain'],
            ['texto' => 'La mejor forma de predecir el futuro es crearlo.', 'fuente' => 'Peter Drucker'],
            ['texto' => 'Todo lo que puedes imaginar es real.', 'fuente' => 'Pablo Picasso'],
            ['texto' => 'La duda es el principio de la sabiduría.', 'fuente' => 'René Descartes'],
            ['texto' => 'Aquel que tiene un porqué para vivir se puede enfrentar a todos los cómos.', 'fuente' => 'Friedrich Nietzsche'],
            ['texto' => 'Solo sé que no sé nada.', 'fuente' => 'Sócrates'],
            ['texto' => 'La felicidad depende de nosotros mismos.', 'fuente' => 'Aristóteles'],
            ['texto' => 'El conocimiento es poder.', 'fuente' => 'Francis Bacon'],
            ['texto' => 'No hay camino para la paz, la paz es el camino.', 'fuente' => 'Mahatma Gandhi'],
            ['texto' => 'Ser o no ser, esa es la cuestión.', 'fuente' => 'William Shakespeare'],
            ['texto' => 'La medida del amor es amar sin medida.', 'fuente' => 'San Agustín'],
            ['texto' => 'El hombre está condenado a ser libre.', 'fuente' => 'Jean-Paul Sartre'],
            ['texto' => 'La vida es lo que pasa mientras estás ocupado haciendo otros planes.', 'fuente' => 'John Lennon'],
            ['texto' => 'Inténtalo de nuevo. Fracasa de nuevo. Fracasa mejor.', 'fuente' => 'Samuel Beckett'],
            ['texto' => 'Pienso, luego existo.', 'fuente' => 'René Descartes'],
            ['texto' => 'Un viaje de mil millas comienza con un solo paso.', 'fuente' => 'Lao Tsé'],
            ['texto' => 'Lo que somos hoy proviene de nuestros pensamientos de ayer.', 'fuente' => 'Buda'],
            ['texto' => 'No dejes para mañana lo que puedas hacer hoy.', 'fuente' => 'Benjamin Franklin'],
            ['texto' => 'El fracaso es simplemente la oportunidad de comenzar de nuevo, esta vez de forma más inteligente.', 'fuente' => 'Henry Ford'],
            ['texto' => 'Donde no hay visión, el pueblo se extravía.', 'fuente' => 'Proverbio'],
            ['texto' => 'Es durante nuestros momentos más oscuros cuando debemos enfocarnos para ver la luz.', 'fuente' => 'Aristóteles Onassis'],
            ['texto' => 'El coraje no es la ausencia de miedo, sino el triunfo sobre él.', 'fuente' => 'Nelson Mandela'],
            ['texto' => 'La excelencia no es un acto, sino un hábito.', 'fuente' => 'Aristóteles'],
            ['texto' => 'Lo difícil se hace de inmediato; lo imposible tarda un poco más.', 'fuente' => 'George Santayana'],
            ['texto' => 'Siempre parece imposible hasta que se hace.', 'fuente' => 'Nelson Mandela'],
        ];

        foreach ($frases as $frase) {
            FraseMotivacional::updateOrCreate(
                ['Frase' => $frase['texto']],
                ['Fuente' => $frase['fuente']],
            );
        }
    }
}
