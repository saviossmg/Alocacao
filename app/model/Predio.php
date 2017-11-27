<?php





/**
 * Predio
 *
 * @Table(name="predio", indexes={@Index(name="fk_predio_unidade1_idx", columns={"Unidade"})})
 * @Entity
 */
class Predio
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
     * @Column(name="Nome", type="string", length=45, nullable=false)
     */
    private $nome;

    /**
     * @var integer
     *
     * @Column(name="Pisos", type="integer", nullable=false)
     */
    private $pisos;

    /**
     * @var boolean
     *
     * @Column(name="Ativo", type="boolean", nullable=false)
     */
    private $ativo;

    /**
     * @var \Unidade
     *
     * @ManyToOne(targetEntity="Unidade")
     * @JoinColumns({
     *   @JoinColumn(name="Unidade", referencedColumnName="Id")
     * })
     */
    private $unidade;


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
     * Set nome
     *
     * @param string $nome
     *
     * @return Predio
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set pisos
     *
     * @param integer $pisos
     *
     * @return Predio
     */
    public function setPisos($pisos)
    {
        $this->pisos = $pisos;

        return $this;
    }

    /**
     * Get pisos
     *
     * @return integer
     */
    public function getPisos()
    {
        return $this->pisos;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return Predio
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set unidade
     *
     * @param \Unidade $unidade
     *
     * @return Predio
     */
    public function setUnidade(\Unidade $unidade = null)
    {
        $this->unidade = $unidade;

        return $this;
    }

    /**
     * Get unidade
     *
     * @return \Unidade
     */
    public function getUnidade()
    {
        return $this->unidade;
    }
}

