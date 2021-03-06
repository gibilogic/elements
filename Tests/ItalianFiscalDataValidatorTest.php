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

use Symfony\Component\Validator\Constraints\IsNull;
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
     * Test for an invalid Constraint.
     *
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testInvalidConstraint()
    {
        $this->validator->validate('', new IsNull());
    }

    /**
     * Null and empty strings should not generate a violation.
     */
    public function testEmptyValues()
    {
        $this->validator->validate(null, new ItalianFiscalData());
        $this->validator->validate('', new ItalianFiscalData());

        $this->assertNoViolation();
    }

    /**
     * Test for a non-string value.
     *
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testNotStringValue()
    {
        $this->validator->validate(['array'], new ItalianFiscalData());
    }

    /**
     * Test for valid fiscal data (both fiscal code and VAT number)
     */
    public function testValidFiscalData()
    {
        $this->validator->validate('RSSMRC60A02F205A', new ItalianFiscalData([
            'canBeFiscalCode' => true,
            'canBeVatNumber' => false,
            'message' => 'myMessage',
        ]));

        $this->validator->validate('01114601006', new ItalianFiscalData([
            'canBeFiscalCode' => false,
            'canBeVatNumber' => true,
            'message' => 'myMessage',
        ]));

        $this->assertNoViolation();
    }

    /**
     * Tests a set of invalid italian fiscal codes.
     *
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
     * Tests a set of invalid italian VAT numbers.
     *
     * @param mixed $value
     * @param string $valueAsString
     * @dataProvider getInvalidVatNumbers
     */
    public function testInvalidVatNumbers($value, $valueAsString)
    {
        $constraint = new ItalianFiscalData([
            'canBeFiscalCode' => false,
            'canBeVatNumber' => true,
            'message' => 'myMessage',
        ]);

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $valueAsString)
            ->assertRaised();
    }

    /**
     * Generates a set of invalid italian fiscal codes.
     * Starting valid code: RSSMRC60A02F205A
     *
     * @return array
     */
    public function getInvalidFiscalCodes()
    {
        return [
            ['random text', '"random text"'], // Random text
            ['RSSMRC 60A02F205A', '"RSSMRC 60A02F205A"'], // Invalid space
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
     * Generates a set of invalid italian VAT numbers.
     * Starting valid number: 01114601006
     *
     * @return array
     */
    public function getInvalidVatNumbers()
    {
        return [
            ['random text', '"random text"'], // Random text
            ['0111460100', '"0111460100"'], // Last character is missing
            ['0111 4601006', '"0111 4601006"'], // Invalid space
            ['01124601006', '"01124601006"'], // Typo
            ['RSSMRC60A02F205A', '"RSSMRC60A02F205A"'], // Valid fiscal code
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
