<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('university_id', auth()->user()->university_id)->get();
        $roles = Role::all();

        return view('vinify.users.index', compact('users', 'roles'));
    }

    public function sendMail($username, $email, $password)
    {
        // Créer une instance de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configurer le serveur SMTP
            $mail->isSMTP(); // Utiliser SMTP
            $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
            $mail->SMTPAuth = true; // Activer l'authentification SMTP
            $mail->Username = 'tshipambalubobo80@gmail.com'; // Votre adresse Gmail
            $mail->Password = "xtry kfmv wqyp wgwt"; // Mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Sécurisé par STARTTLS
            $mail->Port = 587; // Port TLS

            // Destinataires
            $mail->setFrom('tshipambalubobo80@gmail.com', 'Vincent Tshipamba');
            $mail->addAddress($email); // Adresse du destinataire
            // Contenu
            $mail->isHTML(true); // Format d'email HTML
            $mail->Subject = 'Bienvenue en tant qu\'utilisateur sur Vinify !';
            $mail->Body = '
            <section style="max-width: 32rem; padding: 2rem 1.5rem; margin: auto; background-color: #ffffff; color: #333;">
                <header>
                    <a href="#">
                        Vinify
                    </a>
                </header>

                <main style="margin-top: 1rem;">
                    <h2 style="margin-top: 1rem; color: #4a5568;">Bonjour ' . $username . '🤗</h2>

                    <p style="margin-top: 0.5rem; text-align: justify; line-height: 1.75; color: #4a5568; ">
                        Félicitations ! Vous êtes maintenant un administrateur sur notre plateforme de gestion. Vous pouvez vous connecter à votre compte en utilisant les informations suivantes :
                    </p>

                    <p style="margin-top: 0.5rem; line-height: 1.75; color: #4a5568;">
                        <span style="font-weight: 700;">Nom d\'utilisateur : </span> ' . $username . '<br>
                        <span style="font-weight: 700;">Mot de passe : </span> ' . $password . '<br>
                        <span style="font-weight: 700;">URL de connexion : </span> <a href="http://127.0.0.1:8000/login" style="text-decoration: underline; color: #3182ce;">Se connecter</a>
                    </p>

                    <p style="margin-top: 0.5rem; text-align: justify; line-height: 1.75; color: #4a5568; ">
                        N\'hésitez pas à nous contacter en cas de difficultés de connexion 😊
                    </p>

                    <p style="margin-top: 1rem; color: #4a5568;">
                        Merci, <br>
                        L\'équipe Vinify
                    </p>
                </main>

                <footer style="margin-top: 2rem; text-align: center;">
                    <p style="margin-top: 1.5rem; color: #6b7280">
                        Ce courriel a été envoyé à <a href="#" class="text-blue-600 hover:underline dark:text-blue-400" target="_blank">' . $email . '</a>.
                        Si vous préférez ne pas recevoir ce type d\'e-mail, vous pouvez <a href="#" style="color: #1c64f2; ">gérer vos préférences en matière d\'e-mail.</a>.
                    </p>
                    <p style="margin-top: 0.75rem; color: #6b7280">© ' . date('Y') . ' Vinify. Tous les droits sont réservés.</p>
                </footer>
            </section>
            ';

            // Envoyer l'email
            $mail->send();
        } catch (Exception $e) {
            return response()->json(['error', $mail->ErrorInfo]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8'
            ],
            [
                'name.required' => 'Veuillez saisir un nom d\'utilisateur.',
                'email.required.email' => 'Veuillez saisir une adresse mail valide.',
                'email.unique' => 'Cette adresse mail a deja ete prise.',
            ]
        );

        try {
            // Créer l'utilisateur
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);

            if ($request->input('mail')) {
                try {
                    $this->sendMail($validatedData['name'], $validatedData['email'], $validatedData['password']);
                    return back()->with('success', 'L\'utilisateur a été créé avec succès ! Un email a été envoyé à ' . $validatedData['name'] . ' avec les détails du compte.');
                } catch (\Throwable $th) {
                    return back()->with('error', $th->getMessage());
                }
            }
            // Retourner une réponse de succès
            return back()->with('success', 'Utilisateur créé avec succès et rôle attribué.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Une erreur s\'est produite lors de la création de l\'utilisateur: ' . $th->getMessage());
        }
    }
}
