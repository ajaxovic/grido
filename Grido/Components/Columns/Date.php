<?php

/**
 * This file is part of the Grido (http://grido.bugyik.cz)
 *
 * Copyright (c) 2011 Petr Bugyík (http://petr.bugyik.cz)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace Grido\Components\Columns;

/**
 * Date column.
 *
 * @package     Grido
 * @subpackage  Components\Columns
 * @author      Petr Bugyík
 *
 * @property string $dateFormat
 */
class Date extends Column
{
    const FORMAT_TEXT = 'd M Y';
    const FORMAT_DATE = 'd.m.Y';
    const FORMAT_DATETIME = 'd.m.Y H:i:s';

    /** @var string */
    protected $dateFormat = self::FORMAT_DATE;

    /**
     * @param Grido\Grid $grid
     * @param string $name
     * @param string $label
     * @param string $dateFormat
     */
    public function __construct($grid, $name, $label, $dateFormat = NULL)
    {
        parent::__construct($grid, $name, $label);

        if ($dateFormat !== NULL) {
            $this->dateFormat = $dateFormat;
        }
    }

    /**
     * @param string $format
     * @return Date
     */
    public function setDateFormat($format)
    {
        $this->dateFormat = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function formatValue($value)
    {
        if ($value === NULL) {
            return $this->applyReplacement($value);
        } elseif (is_scalar($value)) {
            $value = \Nette\Templating\Helpers::escapeHtml($value);
            $replaced = $this->applyReplacement($value);
            if ($value !== $replaced && is_scalar($replaced)) {
                return $replaced;
            }
        }

        return $value instanceof \DateTime
            ? $value->format($this->dateFormat)
            : date($this->dateFormat, strtotime($value)); //@todo add notice when result is "01.01.1970"
    }

    /**
     * @param mixed $row
     * @return string
     * @internal
     */
    public function renderExport($row)
    {
        $value = $this->getValue($row);
        return $this->formatValue($value);
    }
}
