<?php

/*
 * This file is part of the GiBilogic Elements package.
 *
 * (c) GiBilogic Srl <info@gibilogic.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gibilogic\Elements\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates an italian fiscal data (fiscal code or VAT number).
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 */
class ItalianFiscalDataValidator extends ConstraintValidator
{
    /**
     * Italian fiscal code length
     */
    const FISCAL_CODE_LENGTH = 16;

    /**
     * Italian VAT number length
     */
    const VAT_NUMBER_LENGTH = 11;

    /**
     * Odd-positioned characters conversion used in the fiscal code validation.
     *
     * @var array $oddCharsConversion
     */
    private $oddCharsConversion = [
        '0' => 1, '1' => 0, '2' => 5, '3' => 7, '4' => 9, '5' => 13, '6' => 15, '7' => 17, '8' => 19, '9' => 21,
        'A' => 1, 'B' => 0, 'C' => 5, 'D' => 7, 'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21,
        'K' => 2, 'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8, 'S' => 12, 'T' => 14,
        'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 'Z' => 23
    ];

    /**
     * Even-positioned characters conversion used in the fiscal code validation.
     *
     * @var array $evenCharsConversion
     */
    private $evenCharsConversion = [
        '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,
        'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7, 'I' => 8, 'J' => 9,
        'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18,
        'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24, 'Z' => 25,
    ];

    /**
     * Remainder-to-letter conversion used in the fiscal code validation.
     *
     * @var array $remainderToControlCodeConversion
     */
    private $remainderToControlCodeConversion = [
        '0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H',
        '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P',
        '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T', '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X',
        '24' => 'Y', '25' => 'Z',
    ];

    /**
     * Validates an italian fiscal data; it can be a fiscal code or a VAT number.
     *
     * @param mixed $value
     * @param Constraint $constraint
     *
     * @throws UnexpectedTypeException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ItalianFiscalData) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\ItalianFiscalData');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if ($constraint->canBeFiscalCode && $this->validateFiscalCode($value)) {
            return;
        }

        if ($constraint->canBeVatNumber && $this->validateVatNumber($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->addViolation();
    }

    /**
     * Validates an italian fiscal code.
     *
     * @param string $value
     * @return bool
     */
    protected function validateFiscalCode($value)
    {
        if (strlen($value) != self::FISCAL_CODE_LENGTH) {
            return false;
        }

        $total = 0;
        foreach (str_split(mb_substr($value, 0, 15)) as $position => $char) {
            $total += ($position + 1) % 2 == 0 ? $this->evenCharsConversion[$char] : $this->oddCharsConversion[$char];
        }

        return mb_substr($value, -1) == $this->remainderToControlCodeConversion[(string)($total % 26)];
    }

    /**
     * Validates an italian VAT number.
     *
     * @param string $value
     * @return bool
     */
    protected function validateVatNumber($value)
    {
        if (strlen($value) != self::VAT_NUMBER_LENGTH) {
            return false;
        }

        return $this->luhnValidation($value);
    }

    /**
     * Executes the Luhn validation on the given numeric string.
     *
     * @param string $value
     * @return bool
     */
    private function luhnValidation($value)
    {
        if (!ctype_digit($value)) {
            return false;
        }

        $checkSum = 0;
        $length = strlen($value);

        for ($i = $length - 1; $i >= 0; $i -= 2) {
            $checkSum += $value{$i};
        }
        for ($i = $length - 2; $i >= 0; $i -= 2) {
            $checkSum += array_sum(str_split($value{$i} * 2));
        }

        return 0 !== $checkSum && 0 === $checkSum % 10;
    }
}
