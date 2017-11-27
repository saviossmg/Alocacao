<?php
/**
 * Horarios
 *
 * @Table(name="horarios", indexes={@Index(name="fk_Horarios_registros1_idx", columns={"Turno"}), @Index(name="fk_Horarios_semestre1_idx", columns={"Semestre"})})
 * @Entity
 */
class Horarios
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
     * @Column(name="Descricao", type="string", length=45, nullable=false)
     */
    private $descricao;

    /**
     * @var \DateTime
     *
     * @Column(name="Inicio", type="time", nullable=false)
     */
    private $inicio;

    /**
     * @var \DateTime
     *
     * @Column(name="Fim", type="time", nullable=false)
     */
    private $fim;

    /**
     * @var \Registro
     *
     * @ManyToOne(targetEntity="Registro")
     * @JoinColumns({
     *   @JoinColumn(name="Turno", referencedColumnName="Id")
     * })
     */
    private $turno;

    /**
     * @var \Vwsemestre
     *
     * @ManyToOne(targetEntity="Vwsemestre")
     * @JoinColumns({
     *   @JoinColumn(name="Semestre", referencedColumnName="Id")
     * })
     */
    private $semestre;


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
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Horarios
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     *
     * @return Horarios
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fim
     *
     * @param \DateTime $fim
     *
     * @return Horarios
     */
    public function setFim($fim)
    {
        $this->fim = $fim;

        return $this;
    }

    /**
     * Get fim
     *
     * @return \DateTime
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Set turno
     *
     * @param \Registro $turno
     *
     * @return Horarios
     */
    public function setTurno(\Registro $turno = null)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return \Registro
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set semestre
     *
     * @param \Vwsemestre $semestre
     *
     * @return Horarios
     */
    public function setSemestre(\Vwsemestre $semestre = null)
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * Get semestre
     *
     * @return \Vwsemestre
     */
    public function getSemestre()
    {
        return $this->semestre;
    }
}

