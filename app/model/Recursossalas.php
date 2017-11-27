<?php





/**
 * Recursossalas
 *
 * @Table(name="recursossalas", indexes={@Index(name="fk_registros_has_sala_sala1_idx", columns={"Sala"}), @Index(name="fk_registros_has_sala_registros1_idx", columns={"Registro"})})
 * @Entity
 */
class Recursossalas
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
     * @var integer
     *
     * @Column(name="Quantidade", type="integer", nullable=false)
     */
    private $quantidade;

    /**
     * @var \Registro
     *
     * @ManyToOne(targetEntity="Registro")
     * @JoinColumns({
     *   @JoinColumn(name="Registro", referencedColumnName="Id")
     * })
     */
    private $registro;

    /**
     * @var \Sala
     *
     * @ManyToOne(targetEntity="Sala")
     * @JoinColumns({
     *   @JoinColumn(name="Sala", referencedColumnName="Id")
     * })
     */
    private $sala;


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
     * Set quantidade
     *
     * @param integer $quantidade
     *
     * @return Recursossalas
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get quantidade
     *
     * @return integer
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set registro
     *
     * @param \Registro $registro
     *
     * @return Recursossalas
     */
    public function setRegistro(\Registro $registro = null)
    {
        $this->registro = $registro;

        return $this;
    }

    /**
     * Get registro
     *
     * @return \Registro
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * Set sala
     *
     * @param \Sala $sala
     *
     * @return Recursossalas
     */
    public function setSala(\Sala $sala = null)
    {
        $this->sala = $sala;

        return $this;
    }

    /**
     * Get sala
     *
     * @return \Sala
     */
    public function getSala()
    {
        return $this->sala;
    }
}

