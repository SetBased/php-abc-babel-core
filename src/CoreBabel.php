<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\Helper\Html;
use SetBased\Exception\RuntimeException;

/**
 * A concrete implementation of Babel that retrieves all linguistic entities from a database.
 */
class CoreBabel extends Babel
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the (default) language.
   *
   * @var int
   */
  private $lanId;

  /**
   * The stack with language IDs.
   *
   * @var array
   */
  private $lanIdStack = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $lanId The ID of the default language.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct($lanId)
  {
    parent::__construct();

    $this->lanId = $lanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a text in the default language with special characters converted to HTML entities.
   *
   * @param int $txtId The ID of the text.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtmlText($txtId)
  {
    return Html::txt2Html($this->getText($txtId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a text in the default language using formatting (using
   * [vsprintf](http://php.net/manual/en/function.vsprintf.php)) with special characters converted to HTML entities.
   *
   * @param int   $txtId        The ID of the text.
   * @param bool  $formatIsHtml If true the format is valid HTML code with special characters converted to HTML
   *                            entities. Otherwise, the format is plain text and special characters will be converted
   *                            to HTML entities.
   * @param bool  $argsAreHtml  If true the arguments are valid HTML code with special characters converted to HTML
   *                            entities. Otherwise, the arguments are plain text and special characters will be
   *                            converted to HTML entities.
   * @param array $args         The parameters for the format string.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtmlTextFormatted($txtId, $formatIsHtml, $argsAreHtml, $args)
  {
    $formatIsHtml = !empty($formatIsHtml);
    $argsAreHtml  = !empty($argsAreHtml);

    switch (true)
    {
      case ($formatIsHtml===false && $argsAreHtml===false):
        return Html::txt2Html($this->getTextFormatted($txtId, $args));

      case ($formatIsHtml===false && $argsAreHtml===true):
        $text = Abc::$DL->abcBabelTextGetText($txtId, $this->lanId);

        return vsprintf(Html::txt2Html($text), $args);

      case ($formatIsHtml===true && $argsAreHtml===false):
        $text = Abc::$DL->abcBabelTextGetText($txtId, $this->lanId);

        $tmp = [];
        foreach ($args as $arg)
        {
          $tmp[] = Html::txt2Html($arg);
        }

        return vsprintf($text, $tmp);

      case ($formatIsHtml===true && $argsAreHtml===true):
        return $this->getTextFormatted($txtId, $args);

      default:
        throw new RuntimeException('2**2 > 4');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a text in the default language in which substrings are replaced (using
   * [strtr](http://php.net/manual/en/function.strtr.php)) with special characters converted to HTML entities.
   *
   * @param int   $txtId         The ID of the text.
   * @param bool  $formatIsHtml  If true the text is valid HTML code with special characters converted to HTML
   *                             entities. Otherwise, the text is plain text and special characters will be converted
   *                             to HTML entities.
   * @param bool  $valuesAreHtml If true the replace values are valid HTML code with special characters converted to
   *                             HTML entities. Otherwise, the replace values are plain text and special characters
   *                             will be converted to HTML entities.
   * @param array $replacePairs  The parameters for the format string.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtmlTextReplaced($txtId, $formatIsHtml, $valuesAreHtml, $replacePairs)
  {
    $formatIsHtml  = !empty($formatIsHtml);
    $valuesAreHtml = !empty($valuesAreHtml);

    switch (true)
    {
      case ($formatIsHtml===false && $valuesAreHtml===false):
        return Html::txt2Html($this->getTextReplaced($txtId, $replacePairs));

      case ($formatIsHtml===false && $valuesAreHtml===true):
        $text = Abc::$DL->abcBabelTextGetText($txtId, $this->lanId);

        return strtr(Html::txt2Html($text), $replacePairs);

      case ($formatIsHtml===true && $valuesAreHtml===false):
        $text = Abc::$DL->abcBabelTextGetText($txtId, $this->lanId);

        $tmp = [];
        foreach ($replacePairs as $key => $value)
        {
          $tmp[$key] = Html::txt2Html($value);
        }

        return strtr($text, $tmp);

      case ($formatIsHtml===true && $valuesAreHtml===true):
        return $this->getTextReplaced($txtId, $replacePairs);

      default:
        throw new RuntimeException('2**2 > 4');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a word in the default language with special characters converted to HTML entities.
   *
   * @param int $wrdId The ID of the word.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getHtmlWord($wrdId)
  {
    return Html::txt2Html($this->getWord($wrdId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the language.
   *
   * @return int
   *
   * @since 1.0.0
   * @api
   */
  public function getLanId()
  {
    return $this->lanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of a text.
   *
   * @param int        $txtId The ID of the text.
   * @param array|null $args  The arguments when the text is a format string.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getText($txtId, $args = null)
  {
    $text = Abc::$DL->abcBabelTextGetText($txtId, $this->lanId);

    if (empty($args))
    {
      return $text;
    }

    return vsprintf($text, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a text in the default language using formatting (using
   * [vsprintf](http://php.net/manual/en/function.vsprintf.php)).
   *
   * @param int   $txtId The ID of the text.
   * @param array $args  The parameters for the format string.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getTextFormatted($txtId, $args)
  {
    $text = Abc::$DL->abcBabelTextGetText($txtId, $this->lanId);

    return vsprintf($text, $args);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a text in the default language in which substrings are replaced (using
   * [strtr](http://php.net/manual/en/function.strtr.php)).
   *
   * @param int   $txtId        The ID of the text.
   * @param array $replacePairs The replace pairs.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getTextReplaced($txtId, $replacePairs)
  {
    $text = Abc::$DL->abcBabelTextGetText($txtId, $this->lanId);

    return strtr($text, $replacePairs);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the text of a word.
   *
   * @param int $wrdId The ID of the word.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getWord($wrdId)
  {
    return Abc::$DL->abcBabelWordGetWord($wrdId, $this->lanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Restores the previous default language as the current default language.
   *
   * @return void
   *
   * @since 1.0.0
   * @api
   */
  public function popLanguage()
  {
    $this->lanId = array_pop($this->lanIdStack);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a new default language.
   *
   * @param int $lanId The ID of the new default language.
   *
   * @return void
   *
   * @since 1.0.0
   * @api
   */
  public function pushLanguage($lanId)
  {
    array_push($this->lanIdStack, $this->lanId);

    $this->lanId = $lanId;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
