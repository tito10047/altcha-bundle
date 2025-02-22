<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 22. 2. 2025
 * Time: 18:37
 */

namespace Huluti\AltchaBundle\Tests\Type;

use AceEditorBundle\Form\Extension\AceEditor\Type\AceEditorType;
use Generator;
use Huluti\AltchaBundle\Type\AltchaType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

        $this->formType = new AltchaType(
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
        $this->assertSame(TextType::class, $this->formType->getParent());
    }

    public function testOptionsWidthHeightUnitNormalizer(): void
    {
        $opts = new OptionsResolver();
        $this->formType->configureOptions($opts);

        $resolved = $opts->resolve(['floating' => null, 'hide_logo' => null, 'hide_footer' => null]);
        $this->assertSame( null, $resolved['floating']);
        $this->assertSame( null, $resolved['hide_logo']);
        $this->assertSame(null, $resolved['hide_footer']);

        $resolved = $opts->resolve(['floating' => true, 'hide_logo' => true, 'hide_footer' => true]);
        $this->assertSame(true, $resolved['floating']);
        $this->assertSame( true, $resolved['hide_logo']);
        $this->assertSame( true, $resolved['hide_footer']);

        $resolved = $opts->resolve(['floating' => false, 'hide_logo' => false, 'hide_footer' => false]);
        $this->assertSame( false, $resolved['floating']);
        $this->assertSame( false, $resolved['hide_logo']);
        $this->assertSame( false, $resolved['hide_footer']);

    }

    #[DataProvider('badOptionsProvider')]
    public function testBadOptions(string $option, mixed $value): void
    {
        $opts = new OptionsResolver();
        $this->formType->configureOptions($opts);

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
}