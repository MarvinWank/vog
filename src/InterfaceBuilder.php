<?php
/**
 * Dirk Benkert (https://www.dirk-benkert.de/)
 *
 * @link  https://gitlab.dirk-benkert.de/ for the canonical source repository
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Vog;


use Vog\ValueObjects\Config;

class InterfaceBuilder extends AbstractBuilder
{
    public function __construct(string $name, Config $config)
    {
        parent::__construct($name, $config);
        $this->type = "interface";
        $this->setIsFinal(false);
        $this->setIsMutable(false);
    }

    public function getPhpCode(): string
    {
        $phpcode = $this->generateGenericPhpHeader([], 'interface');
        $phpcode = $this->closeClass($phpcode);

        return $phpcode;
    }
}