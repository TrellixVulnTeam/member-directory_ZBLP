<?php declare(strict_types=1);

namespace PhpParser\Lexer;

use PhpParser\Error;
use PhpParser\ErrorHandler;
use PhpParser\Lexer;
<<<<<<< HEAD
use PhpParser\Lexer\TokenEmulator\AttributeEmulator;
use PhpParser\Lexer\TokenEmulator\CoaleseEqualTokenEmulator;
use PhpParser\Lexer\TokenEmulator\FlexibleDocStringEmulator;
=======
use PhpParser\Lexer\TokenEmulator\CoaleseEqualTokenEmulator;
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
use PhpParser\Lexer\TokenEmulator\FnTokenEmulator;
use PhpParser\Lexer\TokenEmulator\MatchTokenEmulator;
use PhpParser\Lexer\TokenEmulator\NullsafeTokenEmulator;
use PhpParser\Lexer\TokenEmulator\NumericLiteralSeparatorEmulator;
<<<<<<< HEAD
use PhpParser\Lexer\TokenEmulator\ReverseEmulator;
use PhpParser\Lexer\TokenEmulator\TokenEmulator;
=======
use PhpParser\Lexer\TokenEmulator\TokenEmulatorInterface;
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
use PhpParser\Parser\Tokens;

class Emulative extends Lexer
{
    const PHP_7_3 = '7.3dev';
    const PHP_7_4 = '7.4dev';
    const PHP_8_0 = '8.0dev';

<<<<<<< HEAD
    /** @var mixed[] Patches used to reverse changes introduced in the code */
    private $patches = [];

    /** @var TokenEmulator[] */
    private $emulators = [];
=======
    const FLEXIBLE_DOC_STRING_REGEX = <<<'REGEX'
/<<<[ \t]*(['"]?)([a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\1\r?\n
(?:.*\r?\n)*?
(?<indentation>\h*)\2(?![a-zA-Z0-9_\x80-\xff])(?<separator>(?:;?[\r\n])?)/x
REGEX;

    /** @var mixed[] Patches used to reverse changes introduced in the code */
    private $patches = [];

    /** @var TokenEmulatorInterface[] */
    private $tokenEmulators = [];
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd

    /** @var string */
    private $targetPhpVersion;

    /**
     * @param mixed[] $options Lexer options. In addition to the usual options,
     *                         accepts a 'phpVersion' string that specifies the
     *                         version to emulated. Defaults to newest supported.
     */
    public function __construct(array $options = [])
    {
        $this->targetPhpVersion = $options['phpVersion'] ?? Emulative::PHP_8_0;
        unset($options['phpVersion']);

        parent::__construct($options);

<<<<<<< HEAD
        $emulators = [
            new FlexibleDocStringEmulator(),
            new FnTokenEmulator(),
            new MatchTokenEmulator(),
            new CoaleseEqualTokenEmulator(),
            new NumericLiteralSeparatorEmulator(),
            new NullsafeTokenEmulator(),
            new AttributeEmulator(),
        ];

        // Collect emulators that are relevant for the PHP version we're running
        // and the PHP version we're targeting for emulation.
        foreach ($emulators as $emulator) {
            $emulatorPhpVersion = $emulator->getPhpVersion();
            if ($this->isForwardEmulationNeeded($emulatorPhpVersion)) {
                $this->emulators[] = $emulator;
            } else if ($this->isReverseEmulationNeeded($emulatorPhpVersion)) {
                $this->emulators[] = new ReverseEmulator($emulator);
            }
        }
    }

    public function startLexing(string $code, ErrorHandler $errorHandler = null) {
        $emulators = array_filter($this->emulators, function($emulator) use($code) {
            return $emulator->isEmulationNeeded($code);
        });

        if (empty($emulators)) {
=======
        $this->tokenEmulators[] = new FnTokenEmulator();
        $this->tokenEmulators[] = new MatchTokenEmulator();
        $this->tokenEmulators[] = new CoaleseEqualTokenEmulator();
        $this->tokenEmulators[] = new NumericLiteralSeparatorEmulator();
        $this->tokenEmulators[] = new NullsafeTokenEmulator();
    }

    public function startLexing(string $code, ErrorHandler $errorHandler = null) {
        $this->patches = [];

        if ($this->isEmulationNeeded($code) === false) {
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
            // Nothing to emulate, yay
            parent::startLexing($code, $errorHandler);
            return;
        }

<<<<<<< HEAD
        $this->patches = [];
        foreach ($emulators as $emulator) {
            $code = $emulator->preprocessCode($code, $this->patches);
        }

        $collector = new ErrorHandler\Collecting();
        parent::startLexing($code, $collector);
        $this->sortPatches();
=======
        $collector = new ErrorHandler\Collecting();

        // 1. emulation of heredoc and nowdoc new syntax
        $preparedCode = $this->processHeredocNowdoc($code);
        parent::startLexing($preparedCode, $collector);
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
        $this->fixupTokens();

        $errors = $collector->getErrors();
        if (!empty($errors)) {
            $this->fixupErrors($errors);
            foreach ($errors as $error) {
                $errorHandler->handleError($error);
            }
        }

<<<<<<< HEAD
        foreach ($emulators as $emulator) {
            $this->tokens = $emulator->emulate($code, $this->tokens);
        }
    }

    private function isForwardEmulationNeeded(string $emulatorPhpVersion): bool {
        return version_compare(\PHP_VERSION, $emulatorPhpVersion, '<')
            && version_compare($this->targetPhpVersion, $emulatorPhpVersion, '>=');
    }

    private function isReverseEmulationNeeded(string $emulatorPhpVersion): bool {
        return version_compare(\PHP_VERSION, $emulatorPhpVersion, '>=')
            && version_compare($this->targetPhpVersion, $emulatorPhpVersion, '<');
    }

    private function sortPatches()
    {
        // Patches may be contributed by different emulators.
        // Make sure they are sorted by increasing patch position.
        usort($this->patches, function($p1, $p2) {
            return $p1[0] <=> $p2[0];
        });
=======
        foreach ($this->tokenEmulators as $tokenEmulator) {
            $emulatorPhpVersion = $tokenEmulator->getPhpVersion();
            if (version_compare(\PHP_VERSION, $emulatorPhpVersion, '<')
                    && version_compare($this->targetPhpVersion, $emulatorPhpVersion, '>=')
                    && $tokenEmulator->isEmulationNeeded($code)) {
                $this->tokens = $tokenEmulator->emulate($code, $this->tokens);
            } else if (version_compare(\PHP_VERSION, $emulatorPhpVersion, '>=')
                    && version_compare($this->targetPhpVersion, $emulatorPhpVersion, '<')
                    && $tokenEmulator->isEmulationNeeded($code)) {
                $this->tokens = $tokenEmulator->reverseEmulate($code, $this->tokens);
            }
        }
    }

    private function isHeredocNowdocEmulationNeeded(string $code): bool
    {
        // skip version where this works without emulation
        if (version_compare(\PHP_VERSION, self::PHP_7_3, '>=')) {
            return false;
        }

        return strpos($code, '<<<') !== false;
    }

    private function processHeredocNowdoc(string $code): string
    {
        if ($this->isHeredocNowdocEmulationNeeded($code) === false) {
            return $code;
        }

        if (!preg_match_all(self::FLEXIBLE_DOC_STRING_REGEX, $code, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE)) {
            // No heredoc/nowdoc found
            return $code;
        }

        // Keep track of how much we need to adjust string offsets due to the modifications we
        // already made
        $posDelta = 0;
        foreach ($matches as $match) {
            $indentation = $match['indentation'][0];
            $indentationStart = $match['indentation'][1];

            $separator = $match['separator'][0];
            $separatorStart = $match['separator'][1];

            if ($indentation === '' && $separator !== '') {
                // Ordinary heredoc/nowdoc
                continue;
            }

            if ($indentation !== '') {
                // Remove indentation
                $indentationLen = strlen($indentation);
                $code = substr_replace($code, '', $indentationStart + $posDelta, $indentationLen);
                $this->patches[] = [$indentationStart + $posDelta, 'add', $indentation];
                $posDelta -= $indentationLen;
            }

            if ($separator === '') {
                // Insert newline as separator
                $code = substr_replace($code, "\n", $separatorStart + $posDelta, 0);
                $this->patches[] = [$separatorStart + $posDelta, 'remove', "\n"];
                $posDelta += 1;
            }
        }

        return $code;
    }

    private function isEmulationNeeded(string $code): bool
    {
        foreach ($this->tokenEmulators as $emulativeToken) {
            if ($emulativeToken->isEmulationNeeded($code)) {
                return true;
            }
        }

        return $this->isHeredocNowdocEmulationNeeded($code);
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
    }

    private function fixupTokens()
    {
        if (\count($this->patches) === 0) {
            return;
        }

        // Load first patch
        $patchIdx = 0;

        list($patchPos, $patchType, $patchText) = $this->patches[$patchIdx];

        // We use a manual loop over the tokens, because we modify the array on the fly
        $pos = 0;
        for ($i = 0, $c = \count($this->tokens); $i < $c; $i++) {
            $token = $this->tokens[$i];
            if (\is_string($token)) {
<<<<<<< HEAD
                if ($patchPos === $pos) {
                    // Only support replacement for string tokens.
                    assert($patchType === 'replace');
                    $this->tokens[$i] = $patchText;

                    // Fetch the next patch
                    $patchIdx++;
                    if ($patchIdx >= \count($this->patches)) {
                        // No more patches, we're done
                        return;
                    }
                    list($patchPos, $patchType, $patchText) = $this->patches[$patchIdx];
                }

=======
                // We assume that patches don't apply to string tokens
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
                $pos += \strlen($token);
                continue;
            }

            $len = \strlen($token[1]);
            $posDelta = 0;
            while ($patchPos >= $pos && $patchPos < $pos + $len) {
                $patchTextLen = \strlen($patchText);
                if ($patchType === 'remove') {
                    if ($patchPos === $pos && $patchTextLen === $len) {
                        // Remove token entirely
                        array_splice($this->tokens, $i, 1, []);
                        $i--;
                        $c--;
                    } else {
                        // Remove from token string
                        $this->tokens[$i][1] = substr_replace(
                            $token[1], '', $patchPos - $pos + $posDelta, $patchTextLen
                        );
                        $posDelta -= $patchTextLen;
                    }
                } elseif ($patchType === 'add') {
                    // Insert into the token string
                    $this->tokens[$i][1] = substr_replace(
                        $token[1], $patchText, $patchPos - $pos + $posDelta, 0
                    );
                    $posDelta += $patchTextLen;
<<<<<<< HEAD
                } else if ($patchType === 'replace') {
                    // Replace inside the token string
                    $this->tokens[$i][1] = substr_replace(
                        $token[1], $patchText, $patchPos - $pos + $posDelta, $patchTextLen
                    );
=======
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
                } else {
                    assert(false);
                }

                // Fetch the next patch
                $patchIdx++;
                if ($patchIdx >= \count($this->patches)) {
                    // No more patches, we're done
                    return;
                }

                list($patchPos, $patchType, $patchText) = $this->patches[$patchIdx];

                // Multiple patches may apply to the same token. Reload the current one to check
                // If the new patch applies
                $token = $this->tokens[$i];
            }

            $pos += $len;
        }

        // A patch did not apply
        assert(false);
    }

    /**
     * Fixup line and position information in errors.
     *
     * @param Error[] $errors
     */
    private function fixupErrors(array $errors) {
        foreach ($errors as $error) {
            $attrs = $error->getAttributes();

            $posDelta = 0;
            $lineDelta = 0;
            foreach ($this->patches as $patch) {
                list($patchPos, $patchType, $patchText) = $patch;
                if ($patchPos >= $attrs['startFilePos']) {
                    // No longer relevant
                    break;
                }

                if ($patchType === 'add') {
                    $posDelta += strlen($patchText);
                    $lineDelta += substr_count($patchText, "\n");
<<<<<<< HEAD
                } else if ($patchType === 'remove') {
=======
                } else {
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
                    $posDelta -= strlen($patchText);
                    $lineDelta -= substr_count($patchText, "\n");
                }
            }

            $attrs['startFilePos'] += $posDelta;
            $attrs['endFilePos'] += $posDelta;
            $attrs['startLine'] += $lineDelta;
            $attrs['endLine'] += $lineDelta;
            $error->setAttributes($attrs);
        }
    }
}
