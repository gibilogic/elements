<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gibilogic\Elements\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Spec class for the Slugger service.
 */
class SluggerServiceSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Gibilogic\Elements\Service\SluggerService');
    }

    public function it_returns_empty_string_on_empty_parameter()
    {
        $this->slugify(null)->shouldReturn('');
        $this->slugify(false)->shouldReturn('');
        $this->slugify('0')->shouldReturn('0');
    }

    public function it_replaces_spaces_with_default_separator()
    {
        $this->slugify('it remove spaces')->shouldReturn('it-remove-spaces');
    }

    public function it_replaces_spaces_with_custom_separator()
    {
        $this->slugify('it remove spaces', '_')->shouldReturn('it_remove_spaces');
    }

    public function it_transforms_letters_to_lowercase()
    {
        $this->slugify('ToLOWERcaseÀÈÌÒÙ', '_')->shouldReturn('tolowercaseaeiou');
    }

    public function it_removes_new_lines_and_carriage_returns()
    {
        $this->slugify("lot\nof\n\rlines", '_')->shouldReturn('lotoflines');
    }

    public function it_replaces_extended_characters()
    {
        $this->slugify('àáâãåÀÁÂÃÅ')->shouldReturn('aaaaaaaaaa');
        $this->slugify('æÆäÄ')->shouldReturn('aeaeaeae');
        $this->slugify('one&amp;two&three')->shouldReturn('oneandtwoandthree');
        $this->slugify('çÇ©')->shouldReturn('ccc');
        $this->slugify('∂')->shouldReturn('d');
        $this->slugify('èéêëÈÉÊË€')->shouldReturn('eeeeeeeee');
        $this->slugify('ìíîïÌÍÎÏ')->shouldReturn('iiiiiiii');
        $this->slugify('ñÑ')->shouldReturn('nn');
        $this->slugify('òóôõøÒÓÔÕØ')->shouldReturn('oooooooooo');
        $this->slugify('œŒöÖ')->shouldReturn('oeoeoeoe');
        $this->slugify('®')->shouldReturn('r');
        $this->slugify('$')->shouldReturn('s');
        $this->slugify('ß')->shouldReturn('ss');
        $this->slugify('ùúûµÙÚÛ')->shouldReturn('uuuuuuu');
        $this->slugify('üÜ')->shouldReturn('ueue');
        $this->slugify('ÿŸ¥')->shouldReturn('yyy');
        $this->slugify('™')->shouldReturn('tm');
        $this->slugify('∏πΠ')->shouldReturn('pipipi');
        $this->slugify('`d\'alemagne`')->shouldReturn('d-alemagne');
    }

    public function it_removes_special_characters()
    {
        $this->slugify('start |\!"£%/()=?^*§°:.,#@` end')->shouldReturn('start-end');
    }
}
