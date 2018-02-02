<?php

/**
 * Criptografia
 *
 * @Table(name="Criptografia")
 * @Entity
 */
class Criptografia
{
    /**
     * @var integer
     *
     * @Column(name="Id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="Chave", type="string", length=500, nullable=false)
     */
    private $chave;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set chave
     *
     * @param string $chave
     *
     * @return Criptografia
     */
    public function setChave($chave)
    {
        $this->chave = $chave;

        return $this;
    }

    /**
     * Get chave
     *
     * @return string
     */
    public function getChave()
    {
        return $this->chave;
    }
}