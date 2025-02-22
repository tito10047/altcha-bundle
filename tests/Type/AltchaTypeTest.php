<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Tests\Type;

use Generator;
use Huluti\AltchaBundle\Type\AltchaType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AltchaTypeTest extends TestCase
{

    private AltchaType $altchaType;

    public function setUp(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturn('test');

        $this->altchaType = new AltchaType(
            enable: true,
            floating: true,
            useStimulus: true,
            hideLogo: true,
            hideFooter: true,
            jsPath: "test",
            translator: $translator
        );
    }

    public function testGetParent(): void
    {
        $this->assertSame(TextType::class, $this->altchaType->getParent());
    }

    #[DataProvider('goodOptionsProvide')]
    public function testBuildView(string $option, mixed $value):void
    {
        $opts = new OptionsResolver();
        $this->altchaType->configureOptions($opts);
        $resolved = $opts->resolve([$option => $value]);

        $this->assertSame($value, $resolved[$option]);

        $formView = $this->createMock(FormView::class);
        $formInterface = $this->createMock(FormInterface::class);
        $formView->vars = [];
        $this->altchaType->buildView($formView, $formInterface, $resolved);
        $this->assertSame($value, $formView->vars[$option]);
    }

    #[DataProvider('defaultOptionsProvide')]
    public function testdefaultOptionsView(string $option, mixed $value):void
    {
        $opts = new OptionsResolver();
        $this->altchaType->configureOptions($opts);
        $resolved = $opts->resolve([$option => null]);

        $formView = $this->createMock(FormView::class);
        $formInterface = $this->createMock(FormInterface::class);
        $formView->vars = [];
        $this->altchaType->buildView($formView, $formInterface, $resolved);
        $this->assertSame($value, $formView->vars[$option]);
    }


    #[DataProvider('badOptionsProvider')]
    public function testBadOptions(string $option, mixed $value): void
    {
        $opts = new OptionsResolver();
        $this->altchaType->configureOptions($opts);

        $this->expectException(InvalidOptionsException::class);
        $opts->resolve([$option => $value]);
    }

    public static function badOptionsProvider(): Generator
    {
        foreach(["floating", "hide_logo", "hide_footer"] as $option){
            foreach(["foo",1,1.1,[], new \stdClass()] as $value){
                yield "bad option {$option} ".json_encode($value)=>[$option, $value];
            }
        }
    }

    public static function goodOptionsProvide(): Generator
    {
        foreach(["floating", "hide_logo", "hide_footer"] as $option){
            foreach([true, false] as $value){
                yield "good option {$option} ".json_encode($value)=>[$option, $value];
            }
        }
    }
    public static function defaultOptionsProvide(): Generator
    {
        foreach(["floating"=>true, "hide_logo"=>true, "hide_footer"=>true] as $option=>$value){
                yield "good option {$option} ".json_encode($value)=>[$option, $value];
        }
    }
}