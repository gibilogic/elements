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

/**
 * Metadata for the ItalianFiscalDataValidator.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 * @codeCoverageIgnore
 *
 * @author Matteo Guindani https://github.com/Ingannatore
 */
class ItalianFiscalData extends Constraint
{
    /**
     * @var boolean $canBeFiscalCode
     */
    public $canBeFiscalCode = true;

    /**
     * @var boolean $canBeVatNumber
     */
    public $canBeVatNumber = true;

    /**
     * @var string $message
     */
    public $message = 'This value is not a valid italian fiscal data.';
}
