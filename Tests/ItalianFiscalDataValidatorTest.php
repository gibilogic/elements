<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gibilogic\Elements\Tests;

use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Gibilogic\Elements\Validation\Constraints\ItalianFiscalDataValidator;
use Gibilogic\Elements\Validation\Constraints\ItalianFiscalData;

/**
 * Unit tests for the ItalianFiscalDataValidator.
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 * @see ItalianFiscalDataValidator
 * @see \PHPUnit_Framework_TestCase
 */
class ItalianFiscalDataValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @var ItalianFiscalDataValidator $validator
     */
    protected $validator;

    /**
     * Null and empty strings should not generate a violation
     */
    public function testEmptyValues()
    {
        $this->validator->validate(null, new ItalianFiscalData());
        $this->validator->validate('', new ItalianFiscalData());

        $this->assertNoViolation();
    }

    /**
     * @param mixed $value
     * @param string $valueAsString
     * @dataProvider getInvalidFiscalCodes
     */
    public function testInvalidFiscalCodes($value, $valueAsString)
    {
        $constraint = new ItalianFiscalData([
            'canBeFiscalCode' => true,
            'canBeVatNumber' => false,
            'message' => 'myMessage',
        ]);

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $valueAsString)
            ->assertRaised();
    }

    /**
     * @return array
     */
    public function getInvalidFiscalCodes()
    {
        return [
            ['random text', '"random text"'], // Random text
            ['RSSMRC 60A02F205A', '"RSSMRC 60A02F205A"'], // Space inside
            ['RSSMRC60A02F205', '"RSSMRC60A02F205"'], // Missing control character
            ['RSSMRC60A02F205S', '"RSSMRC60A02F205S"'], // Wrong control character
            ['RSSMRC69A02F205A', '"RSSMRC69A02F205A"'], // Wrong year of birth
            ['RSSMRC60S02F205A', '"RSSMRC60S02F205A"'], // Wrong month of birth
            ['RSSMRC60A03F205A', '"RSSMRC60A03F205A"'], // Wrong day of birth
            ['RSSMRC60A02D205A', '"RSSMRC60A02D205A"'], // Wrong city code
            ['RSSMRC60A02F295A', '"RSSMRC60A02F295A"'], // Wrong city code
            ['01114601006', '"01114601006"'], // Valid VAT number
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function createValidator()
    {
        return new ItalianFiscalDataValidator();
    }
}
