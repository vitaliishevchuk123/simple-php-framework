<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use SimplePhpFramework\Authentication\SessionAuthInterface;
use SimplePhpFramework\Controller\AbstractController;
use SimplePhpFramework\Http\RedirectResponse;
use SimplePhpFramework\Http\Request;
use SimplePhpFramework\Http\Response;

class RegisterController extends AbstractController
{
    public function form(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register(Request $request, RegisterForm $form, SessionAuthInterface $auth)
    {
        // 1. Створіть модель форми, яка:
        // Форму заінжектив в метод

        $form->setFields(
            $request->input('email'),
            $request->input('password'),
            $request->input('password_confirmation'),
            $request->input('name'),
        );

        // 2. Валідація
        // Якщо є помилки валідації, додати до сесії та перенаправити на форму

        $form->validate();
        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $error) {
                $request->getSession()->setFlash('error', $error);
            }

            return new RedirectResponse('/register');
        }

        // 3. Зареєструвати користувача, викликавши $form->save()
        $user = $form->save();

        // 4. Додати повідомлення про успішну реєстрацію

        $request->getSession()->setFlash('success', "Користувач {$user->getEmail()} успішно зареєстрований");

        // 5. Увійти до системи під користувачем
        $auth->login($user);

        // 6. Перенаправити на потрібну сторінку

        return new RedirectResponse('/register');
    }
}
