<?php
namespace Uskur\PdfLabel;

/**
 * TCPDF Class to print labels in Avery or custom formats
 * Based on work of Laurent PASSEBECQ <lpasseb@numericable.fr>
 *
 * @author Burak USGURLU <burak@uskur.com.tr>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *         
 */
class PdfLabel extends \TCPDF
{

    /**
     * Left margin of labels
     *
     * @var float
     */
    protected $marginLeft;

    /**
     * Top margin of labels
     *
     * @var float
     */
    protected $marginTop;

    /**
     * Horizontal space between 2 labels
     *
     * @var float
     */
    protected $xSpace;

    /**
     * Vertical space between 2 labels
     *
     * @var float
     */
    protected $ySpace;

    /**
     * Number of labels horizontally
     *
     * @var integer
     */
    protected $xNumber;

    /**
     * Number of labels vertically
     *
     * @var integer
     */
    protected $yNumber;

    /**
     * Width of label
     *
     * @var float
     */
    protected $labelWidth;

    /**
     * Height of label
     *
     * @var float
     */
    protected $labelHeight;

    /**
     * Padding
     *
     * @var float
     */
    protected $labelPadding;

    /**
     * Type of unit for the document
     *
     * @var string
     */
    protected $sheetUnit;

    /**
     * Current x position
     *
     * @var integer
     */
    protected $xPosition;

    /**
     * Current y position
     *
     * @var integer
     */
    protected $yPosition;

    /**
     * Cut lines enabled?
     *
     * @var boolean
     */
    protected $cutLines;

    /**
     * List of label formats
     *
     * @var array
     */
    const LABELS = array(
        '5160' => array(
            'paper-size' => 'LETTER',
            'unit' => 'mm',
            'marginLeft' => 4.7625,
            'marginTop' => 12.7,
            'NX' => 3,
            'NY' => 10,
            'SpaceX' => 3.175,
            'SpaceY' => 0,
            'width' => 66.675,
            'height' => 25.4
        ),
        '5161' => array(
            'paper-size' => 'LETTER',
            'unit' => 'mm',
            'marginLeft' => 0.967,
            'marginTop' => 10.7,
            'NX' => 2,
            'NY' => 10,
            'SpaceX' => 3.967,
            'SpaceY' => 0,
            'width' => 101.6,
            'height' => 25.4
        ),
        '5162' => array(
            'paper-size' => 'LETTER',
            'unit' => 'mm',
            'marginLeft' => 0.97,
            'marginTop' => 20.224,
            'NX' => 2,
            'NY' => 7,
            'SpaceX' => 4.762,
            'SpaceY' => 0,
            'width' => 100.807,
            'height' => 35.72
        ),
        '5163' => array(
            'paper-size' => 'LETTER',
            'unit' => 'mm',
            'marginLeft' => 1.762,
            'marginTop' => 10.7,
            'NX' => 2,
            'NY' => 5,
            'SpaceX' => 3.175,
            'SpaceY' => 0,
            'width' => 101.6,
            'height' => 50.8
        ),
        '5164' => array(
            'paper-size' => 'LETTER',
            'unit' => 'in',
            'marginLeft' => 0.148,
            'marginTop' => 0.5,
            'NX' => 2,
            'NY' => 3,
            'SpaceX' => 0.2031,
            'SpaceY' => 0,
            'width' => 4.0,
            'height' => 3.33
        ),
        '8600' => array(
            'paper-size' => 'LETTER',
            'unit' => 'mm',
            'marginLeft' => 7.1,
            'marginTop' => 19,
            'NX' => 3,
            'NY' => 10,
            'SpaceX' => 9.5,
            'SpaceY' => 3.1,
            'width' => 66.6,
            'height' => 25.4
        ),
        'L7163' => array(
            'paper-size' => 'A4',
            'unit' => 'mm',
            'marginLeft' => 5,
            'marginTop' => 15,
            'NX' => 2,
            'NY' => 7,
            'SpaceX' => 25,
            'SpaceY' => 0,
            'width' => 99.1,
            'height' => 38.1
        ),
        '3422' => array(
            'paper-size' => 'A4',
            'unit' => 'mm',
            'marginLeft' => 0,
            'marginTop' => 8.5,
            'NX' => 3,
            'NY' => 8,
            'SpaceX' => 0,
            'SpaceY' => 0,
            'width' => 70,
            'height' => 35
        ),
        'NewPrint4005' => array(
            'paper-size' => 'A4',
            'unit' => 'mm',
            'marginLeft' => 4,
            'marginTop' => 15,
            'NX' => 2,
            'NY' => 4,
            'SpaceX' => 3,
            'SpaceY' => 0,
            'width' => 99.1,
            'height' => 67.2
        ),
        'L7161' => array(
            'paper-size' => 'A4',
            'unit' => 'mm',
            'marginLeft' => 7.25,
            'marginTop' => 8.7,
            'NX' => 3,
            'NY' => 6,
            'SpaceX' => 2.5,
            'SpaceY' => 0,
            'width' => 63.5,
            'height' => 46.6
        ),
        '90x54' => array(
            'paper-size' => 'A4',
            'unit' => 'mm',
            'marginLeft' => 15,
            'marginTop' => 13.5,
            'NX' => 2,
            'NY' => 5,
            'SpaceX' => 0,
            'SpaceY' => 0,
            'width' => 90,
            'height' => 55,
            'cutLines' => true
        ),
        '138x98' => array(
            'paper-size' => 'A4',
            'unit' => 'mm',
            'marginLeft' => 7,
            'marginTop' => 10.5,
            'NX' => 2,
            'NY' => 2,
            'SpaceX' => 0,
            'SpaceY' => 0,
            'width' => 98,
            'height' => 138,
            'cutLines' => true
        )
    );

    function Header()
    {}

    function Footer()
    {}

    /**
     *
     * @author Burak USGURLU <burak@uskur.com.tr>
     * @param array|string $format
     *            Label type name or dimensions array
     * @param string $unit
     * @param number $posX
     * @param number $posY
     * @throws \Exception
     */
    public function __construct($format, $unit = 'mm', $posX = 1, $posY = 1)
    {
        if (is_array($format)) {
            // Custom format
            $Tformat = $format;
        } else {
            // Built-in format
            if (! isset(PdfLabel::LABELS[$format]))
                throw new \Exception('Unknown label format: ' . $format);
            $Tformat = PdfLabel::LABELS[$format];
        }
        
        parent::__construct('P', $unit, $Tformat['paper-size'], true, 'UTF-8');
        
        $this->setViewerPreferences([
            'PrintScaling' => 'None'
        ]);
        
        $this->sheetUnit = $unit;
        $this->setFormat($Tformat);
        $this->SetMargins(0, 0);
        $this->SetAutoPageBreak(false);
        $this->xPosition = $posX - 2;
        $this->yPosition = $posY - 1;
    }

    /**
     * Initialize class based on label dimensions
     *
     * @param array $format
     */
    protected function setFormat($format)
    {
        $this->marginLeft = $this->convertUnit($format['marginLeft'], $format['unit']);
        $this->marginTop = $this->convertUnit($format['marginTop'], $format['unit']);
        $this->xSpace = $this->convertUnit($format['SpaceX'], $format['unit']);
        $this->ySpace = $this->convertUnit($format['SpaceY'], $format['unit']);
        $this->xNumber = $format['NX'];
        $this->yNumber = $format['NY'];
        $this->labelWidth = $this->convertUnit($format['width'], $format['unit']);
        $this->labelHeight = $this->convertUnit($format['height'], $format['unit']);
        $this->labelPadding = $this->convertUnit(isset($format['padding']) ? $format['padding'] : 3, 'mm');
        $this->cutLines = isset($format['cutLines']) ? $format['cutLines'] : false;
    }

    /**
     * convert units (in to mm, mm to in)
     *
     * @param float $value
     * @param string $src
     * @return number
     */
    protected function convertUnit($value, $src)
    {
        $dest = $this->sheetUnit;
        if ($src != $dest) {
            $a['in'] = 39.37008;
            $a['mm'] = 1000;
            return $value * $a[$dest] / $a[$src];
        } else {
            return $value;
        }
    }

    /**
     * Print label as a TCPDF MultiCell
     *
     * @param string $text
     */
    public function addLabel($text)
    {
        list ($width, $height) = $this->newLabelPosition();
        $this->MultiCell($width, $height, $text, 0, 'L');
    }

    /**
     * Print label as a TCPDF HTMLCell
     *
     * @param string $html
     */
    public function addHtmlLabel($html)
    {
        list ($width, $height) = $this->newLabelPosition();
        $this->writeHTMLCell($width, $height, null, null, $html);
    }
    
    /**
     * Print label as a TCPDF HTMLCell with a background image
     *
     * @param string $html
     */
    public function addHtmlLabelWithBackground($html, $backgroundImage)
    {
        list ($width, $height) = $this->newLabelPosition();
        $this->Image($backgroundImage, $this->GetX() - $this->labelPadding, $this->GetY() - $this->labelPadding, $this->labelWidth, $this->labelHeight);
        $this->writeHTMLCell($width, $height, null, null, $html);
    }

    /**
     * Sets the TCPDF X,Y positions for a new label cell.
     *
     * @return number[] Returns the height and width of the cell.
     */
    protected function newLabelPosition()
    {
        // on a new page if enabled, draw cutlines
        if ($this->xPosition == 0 && $this->cutLines)
            $this->drawCutLines();
        $this->xPosition ++;
        if ($this->xPosition == $this->xNumber) {
            // Row full, we start a new one
            $this->xPosition = 0;
            $this->yPosition ++;
            if ($this->yPosition == $this->yNumber) {
                // End of page reached, we start a new one
                $this->yPosition = 0;
                $this->AddPage();
            }
        }
        
        $this->_PosX = $this->marginLeft + $this->xPosition * ($this->labelWidth + $this->xSpace) + $this->labelPadding;
        $this->_PosY = $this->marginTop + $this->yPosition * ($this->labelHeight + $this->ySpace) + $this->labelPadding;
        $this->SetXY($this->_PosX, $this->_PosY);
        return [
            $this->labelWidth - (2 * $this->labelPadding),
            $this->labelHeight - (2 * $this->labelPadding)
        ];
    }

    protected function drawCutLines()
    {
        $style = array(
            'width' => 0.3,
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => 1,
            'color' => array(
                200,
                200,
                200
            )
        );
        
        for ($i = 0; $i < $this->xNumber; $i ++) {
            $x = $this->marginLeft + ($i * ($this->labelWidth + $this->xSpace));
            if ($this->cutLines === "s") {
                $this->Line($x, 0, $x, $this->marginTop + 1, $style);
                $this->Line($x, $this->getPageHeight() - $this->marginTop - 1, $x, $this->getPageHeight(), $style);
            } else {
                $this->Line($x, 0, $x, $this->getPageHeight(), $style);
            }
            $x = $this->marginLeft + (($i + 1) * ($this->labelWidth + $this->xSpace)) - $this->xSpace;
            if ($this->cutLines === "s") {
                $this->Line($x, 0, $x, $this->marginTop + 1, $style);
                $this->Line($x, $this->getPageHeight() - $this->marginTop - 1, $x, $this->getPageHeight(), $style);
            } else {
                $this->Line($x, 0, $x, $this->getPageHeight(), $style);
            }
        }
        
        for ($i = 0; $i < $this->yNumber; $i ++) {
            $y = $this->marginTop + ($i * ($this->labelHeight + $this->ySpace));
            if ($this->cutLines === "s") {
                $this->Line(0, $y, $this->marginLeft + 1, $y, $style);
                $this->Line($this->getPageWidth() - $this->marginLeft - 1, $y, $this->getPageWidth(), $y, $style);
            } else {
                $this->Line(0, $y, $this->getPageWidth(), $y, $style);
            }
            $y = $this->marginTop + (($i + 1) * ($this->labelHeight + $this->ySpace)) - $this->ySpace;
            if ($this->cutLines === "s") {
                $this->Line(0, $y, $this->marginLeft + 1, $y, $style);
                $this->Line($this->getPageWidth() - $this->marginLeft - 1, $y, $this->getPageWidth(), $y, $style);
            } else {
                $this->Line(0, $y, $this->getPageWidth(), $y, $style);
            }
        }
    }
}
