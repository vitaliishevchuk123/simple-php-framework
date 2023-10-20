<?php


use PHPUnit\Framework\TestCase;
use SimplePhpFramework\Session\Session;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        unset($_SESSION);
    }

    public function test_set_and_get_flash()
    {
        $session = new Session();
        $session->setFlash('success', 'Успішно!');
        $session->setFlash('error', 'Технічно шоколадки...');
        $this->assertTrue($session->hasFlash('success'));
        $this->assertTrue($session->hasFlash('error'));
        $this->assertEquals(['Успішно!'], $session->getFlash('success'));
        $this->assertEquals(['Технічно шоколадки...'], $session->getFlash('error'));
        $this->assertEquals([], $session->getFlash('warning'));
    }
}