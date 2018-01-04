<?php
/**
 * AlocacaoSala
 *
 * @Table(name="AlocacaoSala", indexes={@Index(name="fk_alocacao_has_sala_sala1_idx", columns={"Sala"}), @Index(name="fk_AlocacaoSala_SemestreLetivo1_idx", columns={"Semestre"}), @Index(name="fk_AlocacaoSala_Oferta1_idx", columns={"Oferta"})})
 * @Entity
 */
class Alocacaosala
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
     * @var \Oferta
     *
     * @ManyToOne(targetEntity="Oferta")
     * @JoinColumns({
     *   @JoinColumn(name="Oferta", referencedColumnName="Id")
     * })
     */
    private $oferta;

    /**
     * @var \Semestreletivo
     *
     * @ManyToOne(targetEntity="Semestreletivo")
     * @JoinColumns({
     *   @JoinColumn(name="Semestre", referencedColumnName="Id")
     * })
     */
    private $semestre;

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
     * Set oferta
     *
     * @param \Oferta $oferta
     *
     * @return Alocacaosala
     */
    public function setOferta(\Oferta $oferta = null)
    {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \Oferta
     */
    public function getOferta()
    {
        return $this->oferta;
    }

    /**
     * Set semestre
     *
     * @param \Semestreletivo $semestre
     *
     * @return Alocacaosala
     */
    public function setSemestre(\Semestreletivo $semestre = null)
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * Get semestre
     *
     * @return \Semestreletivo
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

    /**
     * Set sala
     *
     * @param \Sala $sala
     *
     * @return Alocacaosala
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

