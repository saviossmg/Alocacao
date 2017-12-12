<?php
/**
 * Sala
 *
 * @Table(name="Sala", indexes={@Index(name="fk_salas_bloco1_idx", columns={"Predio"}), @Index(name="fk_salas_registros1_idx", columns={"Tipo"})})
 * @Entity
 */
class Sala
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
     * @Column(name="Nome", type="string", length=200, nullable=false)
     */
    private $nome;

    /**
     * @var integer
     *
     * @Column(name="Piso", type="integer", nullable=false)
     */
    private $piso;

    /**
     * @var boolean
     *
     * @Column(name="Ativo", type="boolean", nullable=false)
     */
    private $ativo;

    /**
     * @var \Predio
     *
     * @ManyToOne(targetEntity="Predio")
     * @JoinColumns({
     *   @JoinColumn(name="Predio", referencedColumnName="Id")
     * })
     */
    private $predio;

    /**
     * @var \Registro
     *
     * @ManyToOne(targetEntity="Registro")
     * @JoinColumns({
     *   @JoinColumn(name="Tipo", referencedColumnName="Id")
     * })
     */
    private $tipo;


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
     * @return Sala
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
     * Set piso
     *
     * @param integer $piso
     *
     * @return Sala
     */
    public function setPiso($piso)
    {
        $this->piso = $piso;

        return $this;
    }

    /**
     * Get piso
     *
     * @return integer
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return Sala
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
     * Set predio
     *
     * @param \Predio $predio
     *
     * @return Sala
     */
    public function setPredio(\Predio $predio = null)
    {
        $this->predio = $predio;

        return $this;
    }

    /**
     * Get predio
     *
     * @return \Predio
     */
    public function getPredio()
    {
        return $this->predio;
    }

    /**
     * Set tipo
     *
     * @param \Registro $tipo
     *
     * @return Sala
     */
    public function setTipo(\Registro $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \Registro
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}

